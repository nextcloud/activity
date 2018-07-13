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

/** global: OCA */
/** global: net */


(function () {

	/**
	 * @constructs FilesActivity
	 */
	var FilesActivity = function () {

		var filesActivity = {

			divFilesActivity: null,
			dim: {},

			init: function (dim) {
				filesActivity.currDim = dim;

				filesActivity.divFilesActivity = $('#widget-activity-files');
				filesActivity.divFilesActivityTable =
					$('<table>', {class: 'widget-activity-files-table'});

				var tHead = $('<thead>');
				tHead.append($('<td>', {style: 'padding: 20px 5px 10px 50px'}).text('Filename'));
				tHead.append($('<td>', {style: 'width: 100px'}));
				tHead.append($('<td>', {style: 'width: 150px'}).text('By'));
				tHead.append($('<td>', {style: 'width: 150px'}).text('On'));
				filesActivity.divFilesActivityTable.append(tHead);
				filesActivity.divFilesActivity.append(filesActivity.divFilesActivityTable);

				filesActivity.getFilesActivity();
			},


			getFilesActivity: function () {
				var request = {
					widget: 'activity_files',
					request: 'getFilesActivity'
				};

				net.requestWidget(request, filesActivity.onGetFilesActivity);
			},


			onGetFilesActivity: function (result) {
				if (result.value === undefined) {
					return;
				}
				if (result.value.filesActivity === undefined) {
					return;
				}

				filesActivity.displayFilesActivity(result.value.filesActivity);
			},


			displayFilesActivity: function (activities) {
				filesActivity.divFilesActivityTable.children('tbody').children('tr').remove();
				for (var i = 0; i < activities.length; i++) {
					var activity = activities[i];

					var tr = $('<tr>', {'data-url': activity.url});
					tr.append($('<td>', {style: 'padding-left: 30px'}).text(activity.file));
					tr.append($('<td>').text(filesActivity.formatActivity(activity.fileActivity)));
					tr.append($('<td>').text(activity.owner));
					tr.append($('<td>').text(
						OC.Util.relativeModifiedDate(parseInt(activity.timestamp, 10) * 1000)));

					tr.on('click', function () {
						window.open(OC.generateUrl($(this).attr('data-url')));
					});

					filesActivity.divFilesActivityTable.append(tr);
				}
			},


			onResize: function (dim) {
				var oldDim = filesActivity.currDim;
				filesActivity.currDim = dim;

				if (oldDim.height !== dim.height) {
					filesActivity.getFilesActivity();
				}
			},


			push: function (payload) {
				if (payload.filesActivity === undefined) {
					return;
				}

				filesActivity.getFilesActivity();
			},


			formatActivity: function (activity) {
				switch (activity) {
					case 'file_created':
						return 'Created';
					case 'file_changed':
						return 'Edited';
					case 'file_deleted':
						return 'Deleted';
					case 'file_restored':
						return 'Restored';
					case 'shared':
						return 'Shared';
				}

				return activity;
			}
		};

		$.extend(FilesActivity.prototype, filesActivity);
	};

	OCA.DashBoard.FilesActivity = FilesActivity;
	OCA.DashBoard.filesActivity = new FilesActivity();

})();


