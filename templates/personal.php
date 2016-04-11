<?php
/* Copyright (c) 2014, Joas Schilling nickvergessen@owncloud.com
 * This file is licensed under the Affero General Public License version 3
 * or later. See the COPYING-README file. */

/** @var $l OC_L10N */
/** @var $_ array */

script('activity', 'settings');
style('activity', 'settings');
?>

<form id="activity_notifications" class="section">
	<h2><?php p($l->t('Activity')); ?></h2>
	<table class="grid activitysettings">
		<thead>
			<tr>
				<?php foreach ($_['methods'] as $method => $methodName): ?>
				<th class="small activity_select_group" data-select-group="<?php p($method) ?>">
					<?php p($methodName); ?>
				</th>
				<?php endforeach; ?>
				<th><span id="activity_notifications_msg" class="msg"></span></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($_['activities'] as $activity => $data): ?>
			<tr>
				<?php foreach ($_['methods'] as $method => $methodName): ?>
				<td class="small">
					<input type="checkbox" id="<?php p($activity) ?>_<?php p($method) ?>" name="<?php p($activity) ?>_<?php p($method) ?>"
						value="1" class="<?php p($activity) ?> <?php p($method) ?> checkbox"
						<?php if (!in_array($method, $data['methods'])): ?> disabled="disabled"<?php endif; ?>
						<?php if ($data[$method]): ?> checked="checked"<?php endif; ?> />
					<label for="<?php p($activity) ?>_<?php p($method) ?>">
					</label>
				</td>
				<?php endforeach; ?>
				<td class="activity_select_group" data-select-group="<?php p($activity) ?>">
					<?php echo $data['desc']; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<br />
	<input id="notify_setting_self" name="notify_setting_self" type="checkbox" class="checkbox"
		value="1" <?php if ($_['notify_self']): ?> checked="checked"<?php endif; ?> />
	<label for="notify_setting_self"><?php p($l->t('List your own file actions in the stream')); ?></label>
	<br />
	<input id="notify_setting_selfemail" name="notify_setting_selfemail" type="checkbox" class="checkbox"
		value="1" <?php if ($_['notify_selfemail']): ?> checked="checked"<?php endif; ?> />
	<label for="notify_setting_selfemail"><?php p($l->t('Notify about your own actions via email')); ?></label>
	<br />

	<?php if (empty($_['activity_email'])): ?>
		<br />
		<strong><?php p($l->t('You need to set up your email address before you can receive notification emails.')); ?></strong>
	<?php endif; ?>

	<br />
	<label for="notify_setting_batchtime"><?php p($l->t('Send emails:')); ?></label>
	<select id="notify_setting_batchtime" name="notify_setting_batchtime">
		<option value="0"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_HOURLY): ?> selected="selected"<?php endif; ?>><?php p($l->t('Hourly')); ?></option>
		<option value="1"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_DAILY): ?> selected="selected"<?php endif; ?>><?php p($l->t('Daily')); ?></option>
		<option value="2"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_WEEKLY): ?> selected="selected"<?php endif; ?>><?php p($l->t('Weekly')); ?></option>
	</select>
</form>
