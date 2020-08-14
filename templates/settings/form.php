<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Hendrik Leppelsack <hendrik@leppelsack.de>
 * @author Jan-Christoph Borchardt <hey@jancborchardt.net>
 * @author Joas Schilling <coding@schilljs.com>
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

/** @var array $_ */
/** @var \OCP\IL10N $l */
?>

	<table class="grid activitysettings">
		<tbody>
		<?php foreach ($_['activityGroups'] as $group): ?>
			<tr>
				<th colspan="3" class="group-header"><?php p($group['name']) ?></th>
			</tr>
			<tr>
				<?php foreach ($_['methods'] as $method => $methodName): ?>
					<th class="small activity_select_group" data-select-group="<?php p($method) ?>">
						<?php p($methodName); ?>
					</th>
				<?php endforeach; ?>
				<th><span id="activity_notifications_msg" class="msg"></span></th>
			</tr>
			<?php foreach ($group['activities'] as $activity => $data): ?>
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
		<?php endforeach; ?>
		</tbody>
	</table>

<?php if ($_['email_enabled']) { ?>
	<?php if (!$_['is_email_set']): ?>
		<br />
		<strong><?php p($l->t('You need to set up your email address before you can receive notification emails.')); ?></strong>
	<?php endif; ?>

	<br />
	<label for="notify_setting_batchtime"><?php p($l->t('Send notification emails:')); ?></label>
	<select id="notify_setting_batchtime" name="notify_setting_batchtime">
		<option value="3"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_ASAP): ?> selected="selected"<?php endif; ?>><?php p($l->t('As soon as possible')); ?></option>
		<option value="0"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_HOURLY): ?> selected="selected"<?php endif; ?>><?php p($l->t('Hourly')); ?></option>
		<option value="1"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_DAILY): ?> selected="selected"<?php endif; ?>><?php p($l->t('Daily')); ?></option>
		<option value="2"<?php if ($_['setting_batchtime'] === \OCA\Activity\UserSettings::EMAIL_SEND_WEEKLY): ?> selected="selected"<?php endif; ?>><?php p($l->t('Weekly')); ?></option>
	</select>
<?php }
