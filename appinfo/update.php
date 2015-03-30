<?php

$installedVersion = \OCP\Config::getAppValue('activity', 'installed_version');

if (version_compare($installedVersion, '1.2.2', '<')) {
	$mistakes = [
		['*PREFIX*activity', 'subjectparams'],
		['*PREFIX*activity', 'messageparams'],
		['*PREFIX*activity_mq', 'amq_subjectparams'],
	];

	foreach ($mistakes as $entry) {
		list($table, $column) = $entry;
		$query = \OCP\DB::prepare(
			'DELETE FROM `' . $table . '` WHERE `' . $column . "` NOT LIKE '%]' AND `" . $column . "` NOT LIKE '%}'"
		);
		$numEntries = $query->execute();

		\OC::$server->getLogger()->debug('Deleting ' . $numEntries . ' activities with a broken ' . $column . ' value.', ['app' => 'acitivity']);
	}
}

if (version_compare($installedVersion, '1.2.2', '<')) {
	$update = \OCP\DB::prepare('UPDATE `*PREFIX*activity` SET `app` = ? WHERE `type` = ?');
	$update->execute(array('files_sharing', 'shared'));
	$update = \OCP\DB::prepare('UPDATE `*PREFIX*activity_mq` SET `amq_appid` = ? WHERE `amq_type` = ?');
	$update->execute(array('files_sharing', 'shared'));
}
