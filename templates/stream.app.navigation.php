<?php
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2015-2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
?>
<div id="app-navigation" role="navigation">
	<ul>
	<?php foreach ($_['navigations'] as $navigation) { ?>
		<li<?php if ($_['activeNavigation'] === $navigation['id']): ?> class="active" aria-current="page"<?php endif; ?>>
			<a data-navigation="<?php p($navigation['id']) ?>"
			   href="<?php p($navigation['url']) ?>">
				<?php if (!empty($navigation['icon'])) { ?>
					<img alt="" src="<?php print_unescaped($navigation['icon']); ?>">
					<span><?php p($navigation['name']) ?></span>
				<?php } else { ?>
					<span class="no-icon"><?php p($navigation['name']) ?></span>
				<?php } ?>
			</a>
		</li>
	<?php } ?>
	</ul>

	<div id="app-settings">
		<div id="app-settings-header">
			<button class="settings-button" aria-expanded="false" aria-controls="app-settings-content" aria-label="Activity settings" data-apps-slide-toggle="#app-settings-content">
				<?php p($l->t('Activity settings'));?>
			</button>
		</div>

		<div id="app-settings-content">
			<input type="checkbox"<?php if ($_['rssLink']): ?> checked="checked"<?php endif; ?> id="enable_rss" class="checkbox" />
			<label for="enable_rss"><?php p($l->t('Enable RSS feed'));?></label>

			<span id="rssurl"<?php if (!$_['rssLink']): ?> class="hidden"<?php endif; ?>>
				<label for="feed-link" class="hidden-visually"><?php p($l->t('RSS feed'));?></label>
				<input id="feed-link" class="feed-link" type="text" readonly="readonly" value="<?php p($_['rssLink']); ?>" />
				<button aria-label="<?php p($l->t('Copy RSS feed link')); ?>" class="icon-clippy" data-clipboard-target="#rssurl input"></button>
			</span>
			<div id="activity-personal-settings-link">
				<a href="<?php p($_['personalSettingsLink']); ?>">
					<span class="no-icon"><?php p($l->t('Personal notification settings')); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>
