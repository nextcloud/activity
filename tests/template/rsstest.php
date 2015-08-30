<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
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

class RssTest extends TestCase{
	protected function setUp() {
		parent::setUp();
	}

	public function dataEmpty() {
		return [
			['de', 'http://localhost', 'description', 'Fri, 28 Aug 2015 11:47:14 +0000'],
			['en', 'http://owncloud.org', 'Desc', 'Fri, 28 Aug 2015 11:47:15 +0000'],
		];
	}

	/**
	 * @dataProvider dataEmpty
	 *
	 * @param string $language
	 * @param string $link
	 * @param string $description
	 * @param int $timeStamp
	 * @param string $timeDate
	 */
	public function testEmpty($language, $link, $description, $timeDate) {
		$template = new TemplateResponse('activity', 'rss', [
			'rssLang'		=> $language,
			'rssLink'		=> $link,
			'rssPubDate'	=> $timeDate,
			'description'	=> $description,
			'activities'	=> [],
		], '');

		$this->assertSame(
			'<?xml version="1.0" encoding="UTF-8"?>'
			. "\n" . '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'
			. "\n" . '	<channel>'
			. "\n" . '		<title>Activity feed</title>'
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

	public function dataContent() {
		return [
			[[], ''],
			[
				[
					['activity_id' => 1337, 'subject' => '', 'link' => '', 'timestamp' => 0, 'message' => ''],
				],
				'		<item>'
				. "\n" . '			<guid isPermaLink="false">1337</guid>'
				. "\n" . '		</item>',
			],
			[
				[
					['activity_id' => 42, 'subject' => 'text', 'subjectformatted' => ['full' => '<no>html</no>'], 'link' => 'http://doc.owncloud.org', 'timestamp' => 21, 'message' => 'text2', 'messageformatted' => ['full' => '<no2>html</no2>']],
				],
				'		<item>'
				. "\n" . '			<guid isPermaLink="false">42</guid>'
				. "\n" . '			<title>&lt;no&gt;html&lt;/no&gt;</title>'
				. "\n" . '			<link>http://doc.owncloud.org</link>'
				. "\n" . '			<pubDate>Thu, 01 Jan 1970 00:00:21 +0000</pubDate>'
				. "\n" . '			<description><![CDATA[&lt;no2&gt;html&lt;/no2&gt;]]></description>'
				. "\n" . '		</item>',
			],
			[
				[
					['activity_id' => 42, 'subject' => 'text', 'subjectformatted' => ['full' => "line\nbreak"], 'link' => 'http://doc.owncloud.org', 'timestamp' => 21, 'message' => 'text2', 'messageformatted' => ['full' => "line2\nbreak2"]],
				],
				'		<item>'
				. "\n" . '			<guid isPermaLink="false">42</guid>'
				. "\n" . '			<title>line break</title>'
				. "\n" . '			<link>http://doc.owncloud.org</link>'
				. "\n" . '			<pubDate>Thu, 01 Jan 1970 00:00:21 +0000</pubDate>'
				. "\n" . '			<description><![CDATA[line2<br />break2]]></description>'
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
	public function testContent(array $activities, $expected) {
		$template = new TemplateResponse('activity', 'rss', [
			'rssLang'		=> 'en',
			'rssLink'		=> 'http://owncloud.org',
			'rssPubDate'	=> 'Fri, 28 Aug 2015 11:47:15 +0000',
			'description'	=> 'Desc',
			'activities'	=> $activities,
		], '');
		$rendered = $template->render();

		$prefixStub = '<?xml version="1.0" encoding="UTF-8"?>'
			. "\n" . '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'
			. "\n" . '	<channel>'
			. "\n" . '		<title>Activity feed</title>'
			. "\n" . '		<language>en</language>'
			. "\n" . '		<link>http://owncloud.org</link>'
			. "\n" . '		<description>Desc</description>'
			. "\n" . '		<pubDate>Fri, 28 Aug 2015 11:47:15 +0000</pubDate>'
			. "\n" . '		<lastBuildDate>Fri, 28 Aug 2015 11:47:15 +0000</lastBuildDate>'
			. "\n" . '		<atom:link href="http://owncloud.org" rel="self" type="application/rss+xml" />'
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
