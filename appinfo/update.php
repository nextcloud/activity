<?php

$installedVersion = OCP\Config::getAppValue('activity', 'installed_version');

if (version_compare($installedVersion, '1.2.1', '<')) {
	$mistakes = [
		['*PREFIX*activity', 'subjectparams'],
		['*PREFIX*activity', 'messageparams'],
		['*PREFIX*activity_mq', 'amq_subjectparams'],
	];

	foreach ($mistakes as $entry) {
		list($table, $column) = $entry;
		$query = \OCP\DB::prepare('SELECT `' . $column . '` FROM `' . $table . '` WHERE `' . $column . "` <> '' GROUP BY `" . $column . '`');
		$result = $query->execute();

		while ($row = $result->fetchRow()) {
			if ($row[$column][0] !== 's' && $row[$column][0] !== 'a' && $row[$column][0] !== 'i' && $row[$column][0] !== 'b') {
				// Not something we want to have Oo are we updating twice?
				continue;
			}

			$newValue = json_encode(unserialize($row[$column]));
			$update = \OCP\DB::prepare('UPDATE `' . $table . '` SET `' . $column . '` = ? WHERE `' . $column . '` = ?');
			$update->execute(array($newValue, $row[$column]));
		}
	}
}
