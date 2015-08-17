/*
 * Copyright (c) 2015
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */
$(function(){
	var OCActivity={};

	OCActivity.Filter = {
		filter: undefined,
		currentPage: 0,
		navigation: $('#app-navigation'),

		setFilter: function (filter) {
			if (filter === this.filter) {
				return;
			}

			this.navigation.find('a[data-navigation=' + this.filter + ']').parent().removeClass('active');
			this.currentPage = 0;

			this.filter = filter;
			OC.Util.History.pushState('filter=' + filter);

			OCActivity.InfinitScrolling.container.animate({ scrollTop: 0 }, 'slow');
			OCActivity.InfinitScrolling.container.children().remove();
			$('#emptycontent').addClass('hidden');
			$('#no_more_activities').addClass('hidden');
			$('#loading_activities').removeClass('hidden');
			OCActivity.InfinitScrolling.ignoreScroll = false;

			this.navigation.find('a[data-navigation=' + filter + ']').parent().addClass('active');

			OCActivity.InfinitScrolling.prefill();
		}
	};

	OCActivity.InfinitScrolling = {
		ignoreScroll: false,
		container: $('#container'),
		lastDateGroup: null,
		content: $('#app-content'),

		prefill: function () {
			if (this.content.scrollTop() + this.content.height() > this.container.height() - 100) {
				OCActivity.Filter.currentPage++;

				$.get(
					OC.generateUrl('/apps/activity/activities/fetch'),
					'filter=' + OCActivity.Filter.filter + '&page=' + OCActivity.Filter.currentPage,
					function (data) {
						OCActivity.InfinitScrolling.handleActivitiesCallback(data);
					}
				);
			}
		},

		onScroll: function () {
			if (!OCActivity.InfinitScrolling.ignoreScroll && OCActivity.InfinitScrolling.content.scrollTop() +
			 OCActivity.InfinitScrolling.content.height() > OCActivity.InfinitScrolling.container.height() - 100) {
				OCActivity.Filter.currentPage++;

				OCActivity.InfinitScrolling.ignoreScroll = true;
				$.get(
					OC.generateUrl('/apps/activity/activities/fetch'),
					'filter=' + OCActivity.Filter.filter + '&page=' + OCActivity.Filter.currentPage,
					function (data) {
						OCActivity.InfinitScrolling.handleActivitiesCallback(data);
					}
				);
			}
		},

		handleActivitiesCallback: function (data) {
			var $numActivities = data.length;

			if ($numActivities > 0) {
				for (var i = 0; i < data.length; i++) {
					var $activity = data[i];
					this.appendActivityToContainer($activity);
				}

				// Continue prefill
				this.prefill();

			} else if (OCActivity.Filter.currentPage == 1) {
				// First page is empty - No activities :(
				var $emptyContent = $('#emptycontent');
				$emptyContent.removeClass('hidden');
				if (OCActivity.Filter.filter == 'all') {
					$emptyContent.find('p').text(t('activity', 'This stream will show events like additions, changes & shares'));
				} else {
					$emptyContent.find('p').text(t('activity', 'There are no events for this filter'));
				}
				$('#loading_activities').addClass('hidden');

			} else {
				// Page is empty - No more activities :(
				$('#no_more_activities').removeClass('hidden');
				$('#loading_activities').addClass('hidden');
			}
		},

		appendActivityToContainer: function ($activity) {
			this.makeSureDateGroupExists($activity.relativeTimestamp, $activity.readableTimestamp);
			this.addActivity($activity);
		},

		makeSureDateGroupExists: function($relativeTimestamp, $readableTimestamp) {
			var $lastGroup = this.container.children().last();

			if ($lastGroup.data('date') !== $relativeTimestamp) {
				var $content = '<div class="section activity-section group" data-date="' + escapeHTML($relativeTimestamp) + '">' + "\n"
					+'	<h2>'+"\n"
					+'		<span class="has-tooltip" title="' + escapeHTML($readableTimestamp) + '">' + escapeHTML($relativeTimestamp) + '</span>' + "\n"
					+'	</h2>' + "\n"
					+'	<div class="boxcontainer">' + "\n"
					+'	</div>' + "\n"
					+'</div>';
				$content = $($content);
				OCActivity.InfinitScrolling.processElements($content);
				this.container.append($content);
				this.lastDateGroup = $content;
			}
		},

		addActivity: function($activity) {
			var $content = ''
				+ '<div class="box">' + "\n"
				+ '	<div class="messagecontainer">' + "\n"

				+ '		<div class="activity-icon ' + (($activity.typeicon) ? escapeHTML($activity.typeicon) + ' svg' : '') + '"></div>' + "\n"

				+ '		<div class="activitysubject">' + "\n"
				+ (($activity.link) ? '			<a href="' + $activity.link + '">' + "\n" : '')
				+ '			' + $activity.subjectformatted.markup.trimmed + "\n"
				+ (($activity.link) ? '			</a>' + "\n" : '')
				+ '		</div>' + "\n"

				+'		<span class="activitytime has-tooltip" title="' + escapeHTML($activity.readableDateTimestamp) + '">' + "\n"
				+ '			' + escapeHTML($activity.relativeDateTimestamp) + "\n"
				+'		</span>' + "\n";

			if ($activity.message) {
				$content += '<div class="activitymessage">' + "\n"
					+ $activity.messageformatted.markup.trimmed + "\n"
					+'</div>' + "\n";
			}

			if ($activity.previews && $activity.previews.length) {
				$content += '<br />';
				for (var i = 0; i < $activity.previews.length; i++) {
					var $preview = $activity.previews[i];
					$content += (($preview.link) ? '<a href="' + $preview.link + '">' + "\n" : '')
						+ '<img class="preview' + (($preview.isMimeTypeIcon) ? ' preview-mimetype-icon' : '') + '" src="' + $preview.source + '" alt=""/>' + "\n"
						+ (($preview.link) ? '</a>' + "\n" : '')
				}
			}

			$content += '	</div>' + "\n"
				+'</div>';

			$content = $($content);
			OCActivity.InfinitScrolling.processElements($content);
			this.lastDateGroup.append($content);
		},

		processElements: function (parentElement) {
			$(parentElement).find('.avatar').each(function() {
				var element = $(this);
				element.avatar(element.data('user'), 28);
			});

			$(parentElement).find('.has-tooltip').tooltip({
				placement: 'bottom'
			})
		}
	};

	OCActivity.Filter.setFilter(OCActivity.InfinitScrolling.container.attr('data-activity-filter'));
	OCActivity.InfinitScrolling.content.on('scroll', OCActivity.InfinitScrolling.onScroll);

	OCActivity.Filter.navigation.find('a[data-navigation]').on('click', function (event) {
		OCActivity.Filter.setFilter($(this).attr('data-navigation'));
		event.preventDefault();
	});

	$('#enable_rss').change(function () {
		if (this.checked) {
			$('#rssurl').removeClass('hidden');
		} else {
			$('#rssurl').addClass('hidden');
		}
		$.post(OC.generateUrl('/apps/activity/settings/feed'), 'enable=' + this.checked, function(response) {
			$('#rssurl').val(response.data.rsslink);
		});
	});

	$('#rssurl').on('click', function () {
		$('#rssurl').select();
	});
});

