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

namespace OCA\Activity;


use OCP\Util;

class MockUtilSendMail {
	/**
	 * Mocked out to be testable
	 *
	 * @param string $toaddress
	 * @param string $toname
	 * @param string $subject
	 * @param string $mailtext
	 * @param string $fromaddress
	 * @param string $fromname
	 */
	public function sendMail($toaddress, $toname, $subject, $mailtext, $fromaddress, $fromname) {
		// TODO wait for Swift Mailer in core
		try {
			Util::sendMail($toaddress, $toname, $subject, $mailtext, $fromaddress, $fromname,
				0, '', '', '', '');
		} catch (\Exception $e) {
			Util::writeLog('Activity', 'A problem occurred while sending the e-mail. Please revisit your settings.', Util::ERROR);
		}
	}
}
