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
		content: $('#app-content'),

		prefill: function () {
			if (this.content.scrollTop() + this.content.height() > this.container.height() - 100) {
				OCActivity.Filter.currentPage++;

				$.get(
					OC.generateUrl('/apps/activity/activities/fetch'),
					'filter=' + OCActivity.Filter.filter + '&page=' + OCActivity.Filter.currentPage,
					function (data) {
						if (data.trim().length) {
							OCActivity.InfinitScrolling.appendContent(data);

							// Continue prefill
							OCActivity.InfinitScrolling.prefill();
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
						OCActivity.InfinitScrolling.appendContent(data);
						OCActivity.InfinitScrolling.ignoreScroll = false;

						if (!data.trim().length) {
							// Page is empty - No more activities :(
							$('#no_more_activities').removeClass('hidden');
							$('#loading_activities').addClass('hidden');
							OCActivity.InfinitScrolling.ignoreScroll = true;
						}
					}
				);
			}
		},

		appendContent: function (content) {
			var firstNewGroup = $(content).first(),
				lastGroup = this.container.children().last();

			// Is the first new container the same as the last one?
			if (lastGroup && lastGroup.data('date') === firstNewGroup.data('date')) {
				var appendedBoxes = firstNewGroup.find('.box'),
					lastBoxContainer = lastGroup.find('.boxcontainer');

				// Move content into the last box
				OCActivity.InfinitScrolling.processElements(appendedBoxes);
				lastBoxContainer.append(appendedBoxes);

				// Remove the first box, so it's not duplicated
				content = $(content).slice(1);
			} else {
				content = $(content);
			}

			OCActivity.InfinitScrolling.processElements(content);
			this.container.append(content);
		},

		processElements: function (parentElement) {
			$(parentElement).find('.avatar').each(function() {
				var element = $(this);
				element.avatar(element.data('user'), 28);
			});

			$(parentElement).find('.tooltip').tipsy({
				gravity: 'n'
			});
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

