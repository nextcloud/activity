<?php

/**
 * ownCloud
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Display;

class DisplayTest extends TestCase {
	public function testShow() {
		$output = Display::show([
			'timestamp'		=> time(),
			'user'			=> 'test',
			'affecteduser'	=> 'foobar',
			'app'			=> 'files',
			'link'			=> 'localhost',
			'file'			=> 'A.txt',
			'typeicon'		=> '',
			'subject'		=> 'subject',
			'subjectformatted'		=> [
				'trimmed'	=> 'subject.trimmed',
				'full'		=> 'subject.full',
				'markup'	=>[
					'trimmed'	=> 'subject.markup.trimmed',
					'full'		=> 'subject.markup.full',
				],
			],
			'message'		=> 'message',
			'messageformatted'		=> [
				'trimmed'	=> 'message.trimmed',
				'full'		=> 'message.full',
				'markup'	=>[
					'trimmed'	=> 'message.markup.trimmed',
					'full'		=> 'message.markup.full',
				],
			],
		]);
		$this->assertNotEmpty($output, 'Asserting that the template output is not empty');
	}
}
