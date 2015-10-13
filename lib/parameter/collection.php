<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
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
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Parameter;

use OCP\IL10N;
use OCP\Util;

class Collection implements IParameter {
	/** @var IL10N */
	protected $l;

	/** @var Parameter[] */
	protected $parameters;

	/** @var string */
	protected $random;

	/**
	 * @param IL10N $l
	 * @param string $random
	 */
	public function __construct(IL10N $l, $random) {
		$this->l = $l;
		$this->random = $random;
		$this->parameters = [];
	}

	/**
	 * @param IParameter $parameter
	 */
	public function addParameter(IParameter $parameter) {
		foreach ($this->parameters as $existingParameter) {
			if ($existingParameter->getParameter() === $parameter->getParameter()) {
				return;
			}
		}
		$this->parameters[] = $parameter;
	}

	/**
	 * @return mixed
	 */
	public function getParameter() {
		return $this->random;
	}

	/**
	 * @param bool $allowHtml   Should HTML be used to format the parameter?
	 * @param bool $verbose     Should paths, names, etc be shortened or full length
	 * @return string The formatted parameter
	 */
	public function format($allowHtml, $verbose = false) {
		$parameterList = $plainParameterList = [];

		foreach ($this->parameters as $parameter) {
			$parameterList[] = $parameter->format($allowHtml, $verbose);
			$plainParameterList[] = $parameter->format(false, false);
		}

		return $this->joinParameterList($parameterList, $plainParameterList, $allowHtml);
	}

	/**
	 * Returns a list of grouped parameters
	 *
	 * 2 parameters are joined by "and":
	 * => A and B
	 * Up to 5 parameters are joined by "," and "and":
	 * => A, B, C, D and E
	 * More than 5 parameters are joined by "," and trimmed:
	 * => A, B, C and #n more
	 *
	 * @param array $parameterList
	 * @param array $plainParameterList
	 * @param bool $allowHtml
	 * @return string
	 */
	protected function joinParameterList($parameterList, $plainParameterList, $allowHtml) {
		if (empty($parameterList)) {
			return '';
		}

		$count = sizeof($parameterList);
		$lastItem = array_pop($parameterList);

		if ($count === 1) {
			return $lastItem;
		} else if ($count === 2) {
			$firstItem = array_pop($parameterList);
			return (string) $this->l->t('%s and %s', array($firstItem, $lastItem));
		} else if ($count <= 5) {
			$list = implode($this->l->t(', '), $parameterList);
			return (string) $this->l->t('%s and %s', array($list, $lastItem));
		}

		$firstParams = array_slice($parameterList, 0, 3);
		$firstList = implode($this->l->t(', '), $firstParams);
		$trimmedParams = array_slice($plainParameterList, 3);
		$trimmedList = implode($this->l->t(', '), $trimmedParams);
		if ($allowHtml) {
			return (string) $this->l->n(
				'%s and <strong %s>%n more</strong>',
				'%s and <strong %s>%n more</strong>',
				$count - 3,
				array($firstList, 'class="has-tooltip" title="' . Util::sanitizeHTML($trimmedList) . '"'));
		}
		return (string) $this->l->n('%s and %n more', '%s and %n more', $count - 3, array($firstList));
	}
}
