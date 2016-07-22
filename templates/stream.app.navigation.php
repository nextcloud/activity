<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
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
?>
<div id="app-navigation">
	<?php foreach ($_['navigations'] as $navigationGroup => $navigationEntries) { ?>
		<?php if ($navigationGroup !== 'apps'): ?><ul><?php endif; ?>

		<?php foreach ($navigationEntries as $navigation) { ?>
		<li<?php if ($_['activeNavigation'] === $navigation['id']): ?> class="active"<?php endif; ?>>
			<a data-navigation="<?php p($navigation['id']) ?>" href="<?php p($navigation['url']) ?>">
				<?php p($navigation['name']) ?>
			</a>
		</li>
		<?php } ?>

		<?php if ($navigationGroup !== 'top'): ?></ul><?php endif; ?>
	<?php } ?>

	<div id="app-settings">
		<div id="app-settings-header">
			<button class="settings-button" data-apps-slide-toggle="#app-settings-content"></button>
		</div>

		<div id="app-settings-content">
			<input type="checkbox"<?php if ($_['rssLink']): ?> checked="checked"<?php endif; ?> id="enable_rss" class="checkbox" />
			<label for="enable_rss"><?php p($l->t('Enable RSS feed'));?></label>
			<input id="rssurl"<?php if (!$_['rssLink']): ?> class="hidden"<?php endif; ?> type="text" readonly="readonly" value="<?php p($_['rssLink']); ?>" />
		</div>
	</div>
</div>
