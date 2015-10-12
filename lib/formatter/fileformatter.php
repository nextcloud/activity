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

namespace OCA\Activity\Formatter;

use OC\Files\View;
use OCP\Activity\IEvent;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Util;

class FileFormatter implements IFormatter {
	/** @var View */
	protected $view;
	/** @var IURLGenerator */
	protected $urlGenerator;
	/** @var IL10N */
	protected $l;
	/** @var string */
	protected $user;

	/**
	 * @param View $rootView
	 * @param IURLGenerator $urlGenerator
	 * @param IL10N $l
	 * @param string $user
	 */
	public function __construct(View $rootView, IURLGenerator $urlGenerator, IL10N $l, $user) {
		$this->view = $rootView;
		$this->urlGenerator = $urlGenerator;
		$this->l = $l;
		$this->user = $user;
	}

	/**
	 * @param IEvent $event
	 * @param string $parameter The parameter to be formatted
	 * @param bool $allowHtml   Should HTML be used to format the parameter?
	 * @param bool $verbose     Should paths, names, etc be shortened or full length
	 * @return string The formatted parameter
	 */
	public function format(IEvent $event, $parameter, $allowHtml, $verbose = false) {
		$param = $this->fixLegacyFilename($parameter);
		$is_dir = $this->view->is_dir('/' . $this->user . '/files' . $param);

		if ($is_dir) {
			$linkData = ['dir' => $param];
		} else {
			$parentDir = (substr_count($param, '/') === 1) ? '/' : dirname($param);
			$fileName = basename($param);
			$linkData = [
				'dir' => $parentDir,
				'scrollto' => $fileName,
			];
		}
		$fileLink = $this->urlGenerator->linkTo('files', 'index.php', $linkData);

		$param = trim($param, '/');
		list($path, $name) = $this->splitPathFromFilename($param);
		if ($verbose || $path === '') {
			if (!$allowHtml) {
				return $param;
			}
			return '<a class="filename" href="' . $fileLink . '">' . Util::sanitizeHTML($param) . '</a>';
		}

		if (!$allowHtml) {
			return $name;
		}

		$title = ' title="' . $this->l->t('in %s', array(Util::sanitizeHTML($path))) . '"';
		return '<a class="filename has-tooltip" href="' . $fileLink . '"' . $title . '>' . Util::sanitizeHTML($name) . '</a>';
	}

	/**
	 * Prepend leading slash to filenames of legacy activities
	 * @param string $filename
	 * @return string
	 */
	protected function fixLegacyFilename($filename) {
		if (strpos($filename, '/') !== 0) {
			return '/' . $filename;
		}
		return $filename;
	}

	/**
	 * Split the path from the filename string
	 *
	 * @param string $filename
	 * @return array Array with path and filename
	 */
	protected function splitPathFromFilename($filename) {
		if (strrpos($filename, '/') !== false) {
			return array(
				trim(substr($filename, 0, strrpos($filename, '/')), '/'),
				substr($filename, strrpos($filename, '/') + 1),
			);
		}
		return array('', $filename);
	}
}
