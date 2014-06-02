<?php

/**
 * ownCloud - Activity app
 *
 * @author Frank Karlitschek
 * @copyright 2013 Frank Karlitschek frank@owncloud.org
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// check if the user has the right permissions.
OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('activity');

// rss is of content type text/xml
if (isset($_SERVER['HTTP_ACCEPT']) && stristr($_SERVER['HTTP_ACCEPT'], 'application/rss+xml')) {
	header('Content-Type: application/rss+xml');
} else {
	header('Content-Type: text/xml; charset=UTF-8');
}

// generate and show the rss feed
$tmpl = new \OCP\Template('activity', 'rss');

$tmpl->assign('rssLang', \OC_Preferences::getValue(\OCP\User::getUser(), 'core', 'lang'));
$tmpl->assign('rssLink', \OCP\Util::linkToAbsolute('activity','rss.php'));
$tmpl->assign('rssPubDate', date('r'));
$tmpl->assign('user', \OCP\User::getUser());
$tmpl->assign('activities', \OCA\Activity\Data::read(0, 30, false));

$tmpl->printPage();
