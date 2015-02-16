<?php
/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests\Mock;

use OCP\Activity\IExtension;
use OCP\IL10N;
use OCP\IURLGenerator;

class Extension implements IExtension {
	const TYPE_SHARE_CREATED = 'file_created';
	const TYPE_SHARE_CHANGED = 'file_changed';
	const TYPE_SHARE_DELETED = 'file_deleted';
	const TYPE_SHARE_RESTORED = 'file_restored';

	/** @var IL10N */
	protected $l;

	/** @var IURLGenerator */
	protected $URLGenerator;

	/**
	 * @param IL10N $l
	 * @param IURLGenerator $URLGenerator
	 */
	public function __construct(IL10N $l, $URLGenerator) {
		$this->l = $l;
		$this->URLGenerator = $URLGenerator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNotificationTypes($languageCode) {
		return [
			'type1' => 'Type1 description',
			'type2' => 'Type2 description',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function filterNotificationTypes($types, $filter) {
		switch ($filter) {
			case 'filter1':
				return array_intersect([
					'type1',
				], $types);
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultTypes($method) {
		if ($method === 'stream') {
			return ['type1', 'type2'];
		}

		return ['type2'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function translate($app, $text, $params, $stripPath, $highlightParams, $languageCode) {
		if ($app !== 'app1') {
			return false;
		}

		switch ($text) {
			case 'subject1':
				return vsprintf('Subject1 #%1$s', $params);
			case 'subject2':
				return vsprintf('Subject2 @%2$s #%1$s', $params);
			case 'subject3':
				return vsprintf('Subject3 #%1$s @%2$s', $params);

			default:
				return false;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSpecialParameterList($app, $text) {
		if ($app === 'app1') {
			switch ($text) {
				case 'subject1':
					return [0 => 'file'];
				case 'subject2':
					return [0 => 'file', 1 => 'username'];
			}
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTypeIcon($type) {
		switch ($type) {
			case 'type1':
				return 'icon-type1';

			default:
				return false;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getGroupParameter($activity) {
		if ($activity['app'] === 'app1') {
			switch ($activity['subject']) {
				case 'subject1':
					return 0;
			}
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNavigation() {
		return [
			'apps' => [
				'files' => [
					'id' => 'files',
					'name' => (string) $this->l->t('Files'),
					'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList', ['filter' => 'files']),
				],
			],
			'top' => [],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function isFilterValid($filterValue) {
		return $filterValue === 'filter1';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQueryForFilter($filter) {
		if ($filter === 'app1') {
			return ['`app` = ?', ['app1']];
		}
		return false;
	}
}
