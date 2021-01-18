$(document).ready(function() {
	function saveSettings() {
		OC.msg.startSaving('#activity_notifications_msg');
		var post = $('#activity_notifications').serialize();

		$.post(OC.generateUrl('/apps/activity/settings'), post, function(response) {
			OC.msg.finishedSuccess('#activity_notifications_msg', response.data.message);
		});
	}

	var $activityNotifications = $('#activity_notifications');
	$activityNotifications.find('input[type=checkbox]').change(saveSettings);

	$activityNotifications.find('select').change(saveSettings);

	$activityNotifications.find('.activity_select_group').click(function() {
		var $selectGroup = '#activity_notifications .' + $(this).attr('data-select-group');
		var $filteredBoxes = $($selectGroup).not(':disabled');
		var $checkedBoxes = $filteredBoxes.filter(':checked').length;

		$filteredBoxes.prop('checked', true);
		if ($checkedBoxes === $filteredBoxes.filter(':checked').length) {
			// All values were already selected, so invert it
			$filteredBoxes.prop('checked', false);
		}

		saveSettings();
	});
});

OC.Settings = OC.Settings || {};
OC.Settings = _.extend(OC.Settings, {

	_cachedGroups: null,

	/**
	 * Setup selection box for group selection.
	 *
	 * Values need to be separated by a pipe "|" character.
	 * (mostly because a comma is more likely to be used
	 * for groups)
	 *
	 * @param $elements jQuery element (hidden input) to setup select2 on
	 * @param {Array} [extraOptions] extra options hash to pass to select2
	 * @param {Array} [options] extra options
	 * @param {Array} [options.excludeAdmins=false] flag whether to exclude admin groups
	 */
	setupGroupsSelect: function($elements, extraOptions, options) {
		var self = this;
		options = options || {};
		if ($elements.length > 0) {
			// Let's load the data and THEN init our select
			$.ajax({
				url: OC.linkToOCS('cloud/groups', 2) + 'details',
				dataType: 'json',
				success: function(data) {
					var results = [];

					if (data.ocs.data.groups && data.ocs.data.groups.length > 0) {

						data.ocs.data.groups.forEach(function(group) {
							if (!options.excludeAdmins || group.id !== 'admin') {
								results.push({ id: group.id, displayname: group.displayname });
							}
						})

						// note: settings are saved through a "change" event registered
						// on all input fields
						$elements.select2(_.extend({
							placeholder: t('core', 'Groups'),
							allowClear: true,
							multiple: true,
							toggleSelect: true,
							separator: '|',
							data: { results: results, text: 'displayname' },
							initSelection: function(element, callback) {
								var groups = $(element).val();
								var selection;
								if (groups && results.length > 0) {
									selection = _.map(_.filter((groups || []).split('|').sort(), function(groupId) {
										return results.find(function(group) {
											return group.id === groupId
										}) !== undefined
									}), function(groupId) {
										return {
											id: groupId,
											displayname: results.find(function(group) {
												return group.id === groupId
											}).displayname
										}
									})
								} else if (groups) {
									selection = _.map((groups || []).split('|').sort(), function(groupId) {
										return {
											id: groupId,
											displayname: groupId
										};
									});
								}
								callback(selection);
							},
							formatResult: function(element) {
								return escapeHTML(element.displayname);
							},
							formatSelection: function(element) {
								return escapeHTML(element.displayname);
							},
							escapeMarkup: function(m) {
								// prevent double markup escape
								return m;
							}
						}, extraOptions || {}));
					} else {
						OC.Notification.show(t('settings', 'Group list is empty'), { type: 'error' });
						console.log(data);
					}
				},
				error: function(data) {
					OC.Notification.show(t('settings', 'Unable to retrieve the group list'), { type: 'error' });
					console.log(data);
				}
			});
		}
	}
});
