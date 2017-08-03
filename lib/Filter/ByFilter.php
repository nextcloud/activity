<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
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

namespace OCA\Activity\Filter;


use OCP\Activity\IFilter;
use OCP\IL10N;
use OCP\IURLGenerator;

class ByFilter implements IFilter {

	/** @var IL10N */
	protected $l;

	/** @var IURLGenerator */
	protected $url;

	/**
	 * @param IL10N $l
	 * @param IURLGenerator $url
	 */
	public function __construct(IL10N $l, IURLGenerator $url) {
		$this->l = $l;
		$this->url = $url;
	}

	/**
	 * @return string Lowercase a-z only identifier
	 * @since 9.2.0
	 */
	public function getIdentifier() {
		return 'by';
	}

	/**
	 * @return string A translated string
	 * @since 9.2.0
	 */
	public function getName() {
		return $this->l->t('By others');
	}

	/**
	 * @return int
	 * @since 9.2.0
	 */
	public function getPriority() {
		return 2;
	}

	/**
	 * @return string Full URL to an icon, empty string when none is given
	 * @since 9.2.0
	 */
	public function getIcon() {
		return $this->url->getAbsoluteURL($this->url->imagePath('core', 'places/contacts.svg'));
	}

	/**
	 * @param string[] $types
	 * @return string[] An array of allowed apps from which activities should be displayed
	 * @since 9.2.0
	 */
	public function filterTypes(array $types) {
		return $types;
	}

	/**
	 * @return string[] An array of allowed apps from which activities should be displayed
	 * @since 9.2.0
	 */
	public function allowedApps() {
		return [];
	}
}
