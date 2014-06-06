$(function(){
	var OCActivity={};

	OCActivity.InfinitScrolling = {
		prefill: function () {
			if (content.scrollTop() + content.height() > container.height() - 100) {
				currentPage++;

				//ignoreScroll = true;
				$.get(
					OC.filePath('activity', 'ajax', 'fetch.php'),
					'filter=' + container.attr('data-activity-filter') + '&page=' + currentPage,
					function(data) {
						OCActivity.InfinitScrolling.appendContent(data);
						if (data.length) {
							// Continue prefill
							OCActivity.InfinitScrolling.prefill();
						}
						else if (currentPage == 1) {
							// First page is empty - No activities :(
							$('#no_activities').removeClass('hidden');
							$('#loading_activities').addClass('hidden');
						}
						else {
							// Page is empty - No more activities :(
							$('#no_more_activities').removeClass('hidden');
							$('#loading_activities').addClass('hidden');
						}
					}
				);
			}
		},

		onScroll: function () {
			if (!ignoreScroll && content.scrollTop() + content.height() > container.height() - 100) {
				currentPage++;

				ignoreScroll = true;
				$.get(
					OC.filePath('activity', 'ajax', 'fetch.php'),
					'filter=' + container.attr('data-activity-filter') + '&page=' + currentPage,
					function(data) {
						OCActivity.InfinitScrolling.appendContent(data);
						ignoreScroll = false;

						if (!data.length) {
							// Page is empty - No more activities :(
							$('#no_more_activities').removeClass('hidden');
							$('#loading_activities').addClass('hidden');
							ignoreScroll = true;
						}
					}
				);
			}
		},

		appendContent: function (content) {
			var firstNewGroup = $(content).first(),
				lastGroup = container.children().last();

			// Is the first new container the same as the last one?
			if (lastGroup.data('date') === firstNewGroup.data('date')) {
				var appendedBoxes = firstNewGroup.find('.box'),
					lastBoxContainer = lastGroup.find('.boxcontainer');

				// Move content into the last box
				lastBoxContainer.append(appendedBoxes);

				// Remove the first box, so it's not duplicated
				content = $(content).slice(1);
			}

			container.append(content);

			OCActivity.InfinitScrolling.processElements(container);
		},

		processElements: function (parentElement) {
			$(parentElement).find('.avatar').each(function() {
				var element = $(this);
				element.avatar(element.data('user'), 28);
			});

			$(parentElement).find('.tooltip').tipsy({
				gravity:	's',
				fade:		true
			});
		}
	};

	var container = $('#container'),
		content = $('#app-content'),
		currentPage = 0,
		ignoreScroll = false;

	OCActivity.InfinitScrolling.prefill();
	content.on('scroll', OCActivity.InfinitScrolling.onScroll);
});

