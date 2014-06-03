<?php
/* Copyright (c) 2014, Joas Schilling nickvergessen@owncloud.com
 * This file is licensed under the Affero General Public License version 3
 * or later. See the COPYING-README file. */

/** @var $l OC_L10N */
/** @var $_ array */
?>
<div id="app-navigation">
	<ul>
		<?php foreach ($_['topNavigation'] as $navigation) { ?>
		<li>
			<a
				<?php if ($_['activeNavigation'] == $navigation['id']): ?> class="active"<?php endif; ?>
				data-navigation="<?php p($navigation['id']) ?>"
				href="<?php p($navigation['url']) ?>"
			>
				<?php p($navigation['name']) ?>
			</a>
		</li>
		<?php } ?>
	</ul>
	<div id="app-settings">
		<ul>
			<?php foreach ($_['bottomNavigation'] as $navigation) { ?>
			<li>
				<a href="<?php p($navigation['url']) ?>" data-navigation="<?php p($navigation['id']) ?>">
					<?php p($navigation['name']) ?>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
