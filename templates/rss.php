<?php
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2014-2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
