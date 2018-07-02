<?php
/**
 * Nextcloud - Activity Widget for Dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
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

namespace OCA\Activity\Widgets\Model;


class FilesActivity implements \JsonSerializable {


	/** @var int */
	private $activityId;

	/** @var string */
	private $owner;

	/** @var string */
	private $filename;

	/** @var int */
	private $fileId;

	/** @var string */
	private $fileActivity;

	/** @var int */
	private $timestamp;


	/**
	 * WidgetFrame constructor.
	 *
	 * @param int $activityId
	 */
	public function __construct($activityId) {
		$this->activityId = $activityId;
	}


	/**
	 * @return int
	 */
	public function getActivityId() {
		return $this->activityId;
	}


	/**
	 * @return string
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param string $owner
	 *
	 * @return $this
	 */
	public function setOwner(string $owner) {
		$this->owner = $owner;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @param string $filename
	 *
	 * @return $this
	 */
	public function setFilename(string $filename) {
		$this->filename = $filename;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getFileId() {
		return $this->fileId;
	}

	/**
	 * @param int $fileId
	 *
	 * @return $this
	 */
	public function setFileId(int $fileId) {
		$this->fileId = $fileId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFileActivity() {
		return $this->fileActivity;
	}

	/**
	 * @param string $fileActivity
	 *
	 * @return $this
	 */
	public function setFileActivity($fileActivity) {
		$this->fileActivity = $fileActivity;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @param int $timestamp
	 *
	 * @return $this
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;

		return $this;
	}


	/**
	 * @param $data
	 *
	 * @return FilesActivity
	 */
	public static function fromData($data) {

		$info = new FilesActivity($data['activity_id']);

		$info->setOwner($data['user']);
		$info->setFileActivity($data['type']);
		$info->setFilename(self::noStartSlash($data['object_name']));
		$info->setFileId($data['object_id']);
		$info->setTimestamp($data['timestamp']);

		return $info;
	}


	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			'id'           => $this->getActivityId(),
			'owner'        => $this->getOwner(),
			'filename'     => $this->getFilename(),
			'fileId'       => $this->getFileId(),
			'fileActivity' => $this->getFileActivity(),
			'timestamp'    => $this->getTimestamp()
		];
	}


	public static function noStartSlash($path) {
		if (substr($path, 0, 1) !== '/') {
			return $path;
		}

		return self::noStartSlash(substr($path, 1));
	}

}