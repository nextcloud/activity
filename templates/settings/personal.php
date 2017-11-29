<?php
/**
 * @copyright Copyright (c) 2017 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
script('activity', 'settings');
style('activity', 'settings');
/** @var array $_ */
/** @var \OCP\IL10N $l */
?>

<form id="activity_notifications" class="section">

	<h2><?php p($l->t('Activity')); ?></h2>
	<p class="settings-hint">
		<?php if ($_['email_enabled']) { ?>
			<?php p($l->t('Choose for which activities you want to get an email notification, and which should show up in the activity app stream.')); ?>
		<?php } else { ?>
			<?php p($l->t('Choose which activities you want to see in your stream.')); ?>
		<?php } ?>
	</p>

	<?php print_unescaped($this->inc('settings/form')); ?>

</form>
