<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author John Molakvoæ <skjnldsv@protonmail.com>
 * @author Thomas Citharel <nextcloud@tcit.fr>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity\AppInfo;

use OC\DB\ConnectionAdapter;
use OC\Files\View;
use OC\SystemConfig;
use OCA\Activity\Capabilities;
use OCA\Activity\Consumer;
use OCA\Activity\Dashboard\ActivityWidget;
use OCA\Activity\Data;
use OCA\Activity\FilesHooksStatic;
use OCA\Activity\GroupHelper;
use OCA\Activity\Listener\LoadSidebarScripts;
use OCA\Activity\Listener\SetUserDefaults;
use OCA\Activity\Listener\ShareEventListener;
use OCA\Activity\Listener\UserDeleted;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\NotificationGenerator;
use OCA\Activity\UserSettings;
use OCA\Files\Event\LoadSidebar;
use OCP\Activity\IManager;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IDBConnection;
use OCP\IURLGenerator;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IMailer;
use OCP\RichObjectStrings\IValidator;
use OCP\Share\Events\BeforeShareDeletedEvent;
use OCP\Share\Events\ShareDeletedFromSelfEvent;
use OCP\User\Events\PostLoginEvent;
use OCP\User\Events\UserDeletedEvent;
use OCP\Util;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class Application extends App implements IBootstrap {
	public const APP_ID = 'activity';

	public function __construct(array $params = []) {
		parent::__construct(self::APP_ID, $params);
	}

	/**
	 * @psalm-suppress UndefinedClass
	 */
	public function register(IRegistrationContext $context): void {
		$context->registerService('ActivityDBConnection', function (ContainerInterface $c) {
			$systemConfig = $c->get(SystemConfig::class);
			$factory = new \OC\DB\ConnectionFactory($systemConfig);
			$type = $systemConfig->getValue('dbtype', 'sqlite');
			if (!$factory->isValidType($type)) {
				/** @psalm-suppress InvalidThrow */
				throw new \OC\DatabaseException('Invalid database type');
			}
			$connectionParams = $factory->createConnectionParams('activity_');
			$connection = $factory->getConnection($type, $connectionParams);
			/** @psalm-suppress MissingDependency */
			$connection->getConfiguration()->setSQLLogger($c->get(\OCP\Diagnostics\IQueryLogger::class));
			return $connection;
		});

		/**
		 * @psalm-suppress UndefinedClass
		 */
		$context->registerService('ActivityConnectionAdapter', function (ContainerInterface $c) {
			$systemConfig = $c->get(SystemConfig::class);
			$configPrefix = 'activity_';

			if ($systemConfig->getValue($configPrefix . 'dbuser', null) === null &&
				$systemConfig->getValue($configPrefix . 'dbpassword', null) === null &&
				$systemConfig->getValue($configPrefix . 'dbname', null) === null &&
				$systemConfig->getValue($configPrefix . 'dbhost', null) === null &&
				$systemConfig->getValue($configPrefix . 'dbport', null) === null &&
				$systemConfig->getValue($configPrefix . 'dbdriveroptions', null) === null) {
				return $c->get(IDBConnection::class);
			}

			return new ConnectionAdapter(
				$c->get('ActivityDBConnection')
			);
		});

		$context->registerService(Data::class, function (ContainerInterface $c) {
			return new Data(
				$c->get(IManager::class),
				$c->get('ActivityConnectionAdapter'),
				$c->get(LoggerInterface::class),
			);
		});

		$context->registerService(MailQueueHandler::class, function (ContainerInterface $c) {
			return new MailQueueHandler(
				$c->get(IDateTimeFormatter::class),
				$c->get('ActivityConnectionAdapter'),
				$c->get(IMailer::class),
				$c->get(IURLGenerator::class),
				$c->get(IUserManager::class),
				$c->get(IFactory::class),
				$c->get(IManager::class),
				$c->get(IValidator::class),
				$c->get(IConfig::class),
				$c->get(LoggerInterface::class),
				$c->get(Data::class),
				$c->get(GroupHelper::class),
				$c->get(UserSettings::class),
			);
		});

		// Allow automatic DI for the View, until we migrated to Nodes API
		$context->registerService(View::class, function () {
			return new View('');
		}, false);

		$context->registerCapability(Capabilities::class);
		$context->registerEventListener(LoadSidebar::class, LoadSidebarScripts::class);
		$context->registerEventListener(UserDeletedEvent::class, UserDeleted::class);
		$context->registerEventListener(PostLoginEvent::class, SetUserDefaults::class);
		$context->registerDashboardWidget(ActivityWidget::class);

		$this->registerFilesActivity($context);
	}

	public function boot(IBootContext $context): void {
		$this->registerActivityConsumer();
		$this->registerNotifier();
	}

	/**
	 * Registers the consumer to the Activity Manager
	 */
	private function registerActivityConsumer() {
		$c = $this->getContainer();
		/** @var \OCP\IServerContainer $server */
		$server = $c->getServer();

		$server->getActivityManager()->registerConsumer(function () use ($c) {
			return $c->query(Consumer::class);
		});
	}

	public function registerNotifier() {
		$server = $this->getContainer()->getServer();
		$server->getNotificationManager()->registerNotifierService(NotificationGenerator::class);
	}

	/**
	 * Register the hooks for filesystem operations
	 */
	private function registerFilesActivity(IRegistrationContext $context) {
		// All other events from other apps have to be send via the Consumer
		Util::connectHook('OC_Filesystem', 'post_create', FilesHooksStatic::class, 'fileCreate');
		Util::connectHook('OC_Filesystem', 'post_update', FilesHooksStatic::class, 'fileUpdate');
		Util::connectHook('OC_Filesystem', 'delete', FilesHooksStatic::class, 'fileDelete');
		Util::connectHook('OC_Filesystem', 'rename', FilesHooksStatic::class, 'fileMove');
		Util::connectHook('OC_Filesystem', 'post_rename', FilesHooksStatic::class, 'fileMovePost');
		Util::connectHook('\OCA\Files_Trashbin\Trashbin', 'post_restore', FilesHooksStatic::class, 'fileRestore');
		Util::connectHook('OCP\Share', 'post_shared', FilesHooksStatic::class, 'share');

		$context->registerEventListener(BeforeShareDeletedEvent::class, ShareEventListener::class);
		$context->registerEventListener(ShareDeletedFromSelfEvent::class, ShareEventListener::class);
	}
}
