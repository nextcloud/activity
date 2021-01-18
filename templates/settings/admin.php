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
script('activity', 'admin');
style('activity', 'settings');
/** @var array $_ */
/** @var \OCP\IL10N $l */
?>

<div class="section">

	<h2><?php p($l->t('Notifications')); ?></h2>

	<input id="activity_email_enabled" name="activity_email_enabled" type="checkbox" class="checkbox"
		   value="1" <?php if ($_['email_enabled']) { print_unescaped('checked="checked"'); } ?> />
	<label for="activity_email_enabled"><?php p($l->t('Enable notification emails')); ?></label>
	<br />
	<br />
	<br />

	<p class="settings-hint indent">
		<?php p($l->t('Define groups, such as system users groups, whose activities will not be displayed (except for themselves).')); ?>
		<br />
		<input name="activity_system_users_group_list" id="activity_system_users_group_list" value="<?php p($_['system_users_group_list']) ?>" style="width: 400px" class="noJSAutoUpdate"/>
	</p>

</div>

<form class="section" id="activity_notifications">

	<h2><?php p($l->t('Default settings')); ?></h2>

	<p class="settings-hint">
		<?php p($l->t('Configure the default notification settings for new users.')); ?>
	</p>

	<?php print_unescaped($this->inc('settings/form')); ?>

</form>

