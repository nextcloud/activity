<?php
/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Extension;

use OCA\Activity\Data;
use OCP\Activity\IExtension;
use OCP\IL10N;
use OCP\IURLGenerator;

class Files implements IExtension {
	/** @var IL10N */
	protected $l;

	/** @var IURLGenerator */
	protected $URLGenerator;

	/**
	 * @param IL10N $l
	 * @param IURLGenerator $URLGenerator
	 */
	public function __construct(IL10N $l, $URLGenerator) {
		$this->l = $l;
		$this->URLGenerator = $URLGenerator;
	}

	/**
	 * The extension can return an array of additional notification types.
	 * If no additional types are to be added false is to be returned
	 *
	 * @param string $languageCode
	 * @return array|false
	 */
	public function getNotificationTypes($languageCode) {
		$l = \OCP\Util::getL10N('activity', $languageCode);
		return [
			Data::TYPE_SHARED => (string) $l->t('A file or folder has been <strong>shared</strong>'),
			Data::TYPE_SHARE_CREATED => (string) $l->t('A new file or folder has been <strong>created</strong>'),
			Data::TYPE_SHARE_CHANGED => (string) $l->t('A file or folder has been <strong>changed</strong>'),
			Data::TYPE_SHARE_DELETED => (string) $l->t('A file or folder has been <strong>deleted</strong>'),
			Data::TYPE_SHARE_RESTORED => (string) $l->t('A file or folder has been <strong>restored</strong>'),
		];
	}

	/**
	 * The extension can filter the types based on the filter if required.
	 * In case no filter is to be applied false is to be returned unchanged.
	 *
	 * @param array $types
	 * @param string $filter
	 * @return array|false
	 */
	public function filterNotificationTypes($types, $filter) {
		switch ($filter) {
			case 'shares':
				return array_intersect([Data::TYPE_SHARED], $types);
			case 'files':
				return array_intersect([
					Data::TYPE_SHARED,
					Data::TYPE_SHARE_CREATED,
					Data::TYPE_SHARE_CHANGED,
					Data::TYPE_SHARE_DELETED,
					Data::TYPE_SHARE_RESTORED,
				], $types);
		}
		return false;
	}

	/**
	 * For a given method additional types to be displayed in the settings can be returned.
	 * In case no additional types are to be added false is to be returned.
	 *
	 * @param string $method
	 * @return array|false
	 */
	public function getDefaultTypes($method) {
		$settings = array();
		if ($method === 'stream') {
			$settings[] = Data::TYPE_SHARE_CREATED;
			$settings[] = Data::TYPE_SHARE_CHANGED;
			$settings[] = Data::TYPE_SHARE_DELETED;
			$settings[] = Data::TYPE_SHARE_RESTORED;
		}

		if ($method === 'stream' || $method === 'email') {
			$settings[] = Data::TYPE_SHARED;
		}
		return !empty($settings) ? $settings : false;
	}

	/**
	 * The extension can translate a given message to the requested languages.
	 * If no translation is available false is to be returned.
	 *
	 * @param string $app
	 * @param string $text
	 * @param array $params
	 * @param boolean $stripPath
	 * @param boolean $highlightParams
	 * @param string $languageCode
	 * @return string|false
	 */
	public function translate($app, $text, $params, $stripPath, $highlightParams, $languageCode) {
		if ($app !== 'files') {
			return false;
		}

		switch ($text) {
			case 'created_self':
				return (string) $this->l->t('You created %1$s', $params);
			case 'created_by':
				return (string) $this->l->t('%2$s created %1$s', $params);
			case 'created_public':
				return (string) $this->l->t('%1$s was created in a public folder', $params);
			case 'changed_self':
				return (string) $this->l->t('You changed %1$s', $params);
			case 'changed_by':
				return (string) $this->l->t('%2$s changed %1$s', $params);
			case 'deleted_self':
				return (string) $this->l->t('You deleted %1$s', $params);
			case 'deleted_by':
				return (string) $this->l->t('%2$s deleted %1$s', $params);
			case 'restored_self':
				return (string) $this->l->t('You restored %1$s', $params);
			case 'restored_by':
				return (string) $this->l->t('%2$s restored %1$s', $params);
			case 'shared_user_self':
				return (string) $this->l->t('You shared %1$s with %2$s', $params);
			case 'shared_group_self':
				return (string) $this->l->t('You shared %1$s with group %2$s', $params);
			case 'shared_with_by':
				return (string) $this->l->t('%2$s shared %1$s with you', $params);
			case 'shared_link_self':
				return (string) $this->l->t('You shared %1$s via link', $params);
			default:
				return false;
		}
	}

	/**
	 * The extension can define the type of parameters for translation
	 *
	 * Currently known types are:
	 * * file		=> will strip away the path of the file and add a tooltip with it
	 * * username	=> will add the avatar of the user
	 *
	 * @param string $app
	 * @param string $text
	 * @return array|false
	 */
	function getSpecialParameterList($app, $text) {
		if ($app === 'files' && $text === 'shared_group_self') {
			return [0 => 'file'];
		} else if ($app === 'files') {
			switch ($text) {
				case 'created_self':
				case 'created_by':
				case 'created_public':
				case 'changed_self':
				case 'changed_by':
				case 'deleted_self':
				case 'deleted_by':
				case 'restored_self':
				case 'restored_by':
				case 'shared_user_self':
				case 'shared_with_by':
				case 'shared_link_self':
					return [0 => 'file', 1 => 'username'];
			}
		}

		return false;
	}

	/**
	 * A string naming the css class for the icon to be used can be returned.
	 * If no icon is known for the given type false is to be returned.
	 *
	 * @param string $type
	 * @return string|false
	 */
	public function getTypeIcon($type) {
		switch ($type) {
			case Data::TYPE_SHARE_CHANGED:
				return 'icon-change';
			case Data::TYPE_SHARE_CREATED:
				return 'icon-add-color';
			case Data::TYPE_SHARE_DELETED:
				return 'icon-delete-color';
			case Data::TYPE_SHARED:
				return 'icon-share';
			default:
				return false;
		}
	}

	/**
	 * The extension can define the parameter grouping by returning the index as integer.
	 * In case no grouping is required false is to be returned.
	 *
	 * @param array $activity
	 * @return integer|false
	 */
	public function getGroupParameter($activity) {
		if ($activity['app'] === 'files') {
			switch ($activity['subject']) {
				case 'created_self':
				case 'created_by':
				case 'changed_self':
				case 'changed_by':
				case 'deleted_self':
				case 'deleted_by':
				case 'restored_self':
				case 'restored_by':
					return 0;
			}
		}

		return false;
	}

	/**
	 * The extension can define additional navigation entries. The array returned has to contain two keys 'top'
	 * and 'apps' which hold arrays with the relevant entries.
	 * If no further entries are to be added false is no be returned.
	 *
	 * @return array|false
	 */
	public function getNavigation() {
		return [
			'top' => ['shares' => [
				'id' => 'shares',
				'name' => (string) $this->l->t('Shares'),
				'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList', ['filter' => 'shares']),
			]],
			'apps' => ['files' => [
				'id' => 'files',
				'name' => (string) $this->l->t('Files'),
				'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList', ['filter' => 'files']),
			]],
		];
	}

	/**
	 * The extension can check if a customer filter (given by a query string like filter=abc) is valid or not.
	 *
	 * @param string $filterValue
	 * @return boolean
	 */
	public function isFilterValid($filterValue) {
		switch ($filterValue) {
			case 'shares':
			case 'files':
				return true;
		}
		return false;
	}

	/**
	 * For a given filter the extension can specify the sql query conditions including parameters for that query.
	 * In case the extension does not know the filter false is to be returned.
	 * The query condition and the parameters are to be returned as array with two elements.
	 * E.g. return array('`app` = ? and `message` like ?', array('mail', 'ownCloud%'));
	 *
	 * @param string $filter
	 * @return array|false
	 */
	public function getQueryForFilter($filter) {
		switch ($filter) {
			case 'files':
				return [' AND `app` = ?', ['files']];
		}
		return false;
	}
}
