<?php
/* Copyright (c) 2014, Joas Schilling nickvergessen@owncloud.com
 * This file is licensed under the Affero General Public License version 3
 * or later. See the COPYING-README file. */

/** @var $l OC_L10N */
/** @var $_ array */
?>
<div id="app-navigation">
	<ul>
		<li>
			<a class="active" href="#">
				<?php p($l->t('All Activities')) ?>
			</a>
		</li>
	</ul>
	<div id="app-settings">
		<ul>
			<li>
				<a href="<?php p($_['rsslink']) ?>">
					<?php p($l->t('RSS feed')) ?>
				</a>
			</li>
		</ul>
	</div>
</div>
