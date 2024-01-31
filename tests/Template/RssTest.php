<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity\Tests\Template;

use OCA\Activity\Tests\TestCase;
use OCP\AppFramework\Http\TemplateResponse;

class RssTest extends TestCase {
	public function dataEmpty(): array {
		return [
			['de', 'http://localhost', 'title', 'description', 'Fri, 28 Aug 2015 11:47:14 +0000'],
			['en', 'http://nextcloud.org', 'The title', 'Desc', 'Fri, 28 Aug 2015 11:47:15 +0000'],
		];
	}

	/**
	 * @dataProvider dataEmpty
	 *
	 * @param string $language
	 * @param string $link
	 * @param string $description
	 * @param string $timeDate
	 */
	public function testEmpty(string $language, string $link, string $title, string $description, string $timeDate): void {
		$template = new TemplateResponse('activity', 'rss', [
			'rssLang' => $language,
			'rssLink' => $link,
			'rssPubDate' => $timeDate,
			'description' => $description,
			'title' => $title,
			'activities' => [],
		], '');

		$this->assertSame(
			'<?xml version="1.0" encoding="UTF-8"?>'
			. "\n" . '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'
			. "\n" . '	<channel>'
			. "\n" . '		<title>' . $title . '</title>'
			. "\n" . '		<language>' . $language . '</language>'
			. "\n" . '		<link>' . $link . '</link>'
			. "\n" . '		<description>' . $description . '</description>'
			. "\n" . '		<pubDate>' . $timeDate . '</pubDate>'
			. "\n" . '		<lastBuildDate>' . $timeDate . '</lastBuildDate>'
			. "\n" . '		<atom:link href="' . $link . '" rel="self" type="application/rss+xml" />'
			. "\n" . '	</channel>'
			. "\n" . '</rss>' . "\n",
			$template->render()
		);
	}

	public function dataContent(): array {
		return [
			[[], ''],
			[
				[
					['activity_id' => 1337, 'subject' => '', 'link' => '', 'timestamp' => 0, 'message' => ''],
				],
				'		<item>'
				. "\n" . '			<guid isPermaLink="false">http://nextcloud.org?id=1337</guid>'
				. "\n" . '		</item>',
			],
			[
				[
					['activity_id' => 42, 'subject' => 'text', 'link' => 'http://docs.nextcloud.org', 'timestamp' => 21, 'message' => 'text2'],
				],
				'		<item>'
				. "\n" . '			<guid isPermaLink="false">http://nextcloud.org?id=42</guid>'
				. "\n" . '			<title>text</title>'
				. "\n" . '			<link>http://docs.nextcloud.org</link>'
				. "\n" . '			<pubDate>Thu, 01 Jan 1970 00:00:21 +0000</pubDate>'
				. "\n" . '			<description><![CDATA[text2]]></description>'
				. "\n" . '		</item>',
			],
			[
				[
					['activity_id' => 42, 'subject' => 'text', 'link' => 'http://docs.nextcloud.org', 'timestamp' => 21],
				],
				'		<item>'
				. "\n" . '			<guid isPermaLink="false">http://nextcloud.org?id=42</guid>'
				. "\n" . '			<title>text</title>'
				. "\n" . '			<link>http://docs.nextcloud.org</link>'
				. "\n" . '			<pubDate>Thu, 01 Jan 1970 00:00:21 +0000</pubDate>'
				. "\n" . '		</item>',
			],
		];
	}

	/**
	 * @dataProvider dataContent
	 *
	 * @param array $activities
	 * @param string $expected
	 */
	public function testContent(array $activities, string $expected): void {
		$template = new TemplateResponse('activity', 'rss', [
			'rssLang' => 'en',
			'rssLink' => 'http://nextcloud.org',
			'rssPubDate' => 'Fri, 28 Aug 2015 11:47:15 +0000',
			'description' => 'Desc',
			'title' => 'Activity feed',
			'activities' => $activities,
		], '');
		$rendered = $template->render();

		$prefixStub = '<?xml version="1.0" encoding="UTF-8"?>'
			. "\n" . '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'
			. "\n" . '	<channel>'
			. "\n" . '		<title>Activity feed</title>'
			. "\n" . '		<language>en</language>'
			. "\n" . '		<link>http://nextcloud.org</link>'
			. "\n" . '		<description>Desc</description>'
			. "\n" . '		<pubDate>Fri, 28 Aug 2015 11:47:15 +0000</pubDate>'
			. "\n" . '		<lastBuildDate>Fri, 28 Aug 2015 11:47:15 +0000</lastBuildDate>'
			. "\n" . '		<atom:link href="http://nextcloud.org" rel="self" type="application/rss+xml" />'
			. "\n";
		$suffixStub = "\n" . '	</channel>'
			. "\n" . '</rss>' . "\n";

		$this->assertStringStartsWith(
			$prefixStub,
			$rendered
		);

		$this->assertStringEndsWith(
			$suffixStub,
			$rendered
		);

		$rendered = substr($rendered, strlen($prefixStub), 0 - strlen($suffixStub));
		$this->assertEquals($expected, $rendered);
	}
}
