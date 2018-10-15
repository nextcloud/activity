<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Jan-Christoph Borchardt <hey@jancborchardt.net>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
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
script('activity', [
	'richObjectStringParser',
	'templates',
	'script',
	'feedSettings',
]);
style('activity', 'style');
?>

<?php $_['appNavigation']->printPage(); ?>

<div id="app-content">
	<div id="emptycontent" class="hidden">
		<div class="icon-activity"></div>
		<h2><?php p($l->t('No activity yet')); ?></h2>
		<p><?php p($l->t('This stream will show events like additions, changes & shares')); ?></p>
	</div>

	<div id="container" data-activity-filter="<?php p($_['filter']) ?>" data-avatars-enabled="<?php p($_['avatars']) ?>">
	</div>

	<div id="loading_activities" class="icon-loading"></div>

	<div id="no_more_activities" class="hidden">
		<?php p($l->t('No more events to load')) ?>
	</div>
</div>
