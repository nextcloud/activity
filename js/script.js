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
			container.append(content);
			OCActivity.InfinitScrolling.processElements(container);
		},

		processElements: function (parentElement) {
			$(parentElement).find('.avatar').each(function() {
				var element = $(this);
				console.log($(this));
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

