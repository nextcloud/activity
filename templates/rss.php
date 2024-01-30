<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
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
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?php p($_['title']); ?></title>
		<language><?php p($_['rssLang']); ?></language>
		<link><?php p($_['rssLink']); ?></link>
		<description><?php p($_['description']); ?></description>
		<pubDate><?php p($_['rssPubDate']); ?></pubDate>
		<lastBuildDate><?php p($_['rssPubDate']); ?></lastBuildDate>
		<atom:link href="<?php p($_['rssLink']); ?>" rel="self" type="application/rss+xml" />
<?php foreach ($_['activities'] as $activity) { ?>
		<item>
			<guid isPermaLink="false"><?php p($_['rssLink'] . '?id=' . $activity['activity_id']); ?></guid>
<?php if (!empty($activity['subject'])): ?>
			<title><?php p(str_replace("\n", ' ', $activity['subject'])); ?></title>
<?php endif; ?>
<?php if (!empty($activity['link'])): ?>
			<link><?php p($activity['link']); ?></link>
<?php endif; ?>
<?php if (!empty($activity['timestamp'])): ?>
			<pubDate><?php p(date('r', $activity['timestamp'])); ?></pubDate>
<?php endif; ?>
<?php if (!empty($activity['message'])): ?>
			<description><![CDATA[<?php print_unescaped(str_replace("\n", '<br />', \OCP\Util::sanitizeHTML($activity['message']))); ?>]]></description>
<?php endif; ?>
		</item>
<?php } ?>
	</channel>
</rss>
