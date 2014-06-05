$(function(){

	function processChildren(parentElement){
		parentElement.find('.avatar').each(function(){
			var $this = $(this);
			$this.avatar($this.data('user'), 28);
		});
		parentElement.find('.tooltip').tipsy({gravity:'s', fade:true});
	}

	function _onScroll() {
		if (content.scrollTop() + content.height() > container.height() - 100 && !ignoreScroll) {
			currentPage++;

			ignoreScroll = true;
			$.get(
				OC.filePath('activity', 'ajax', 'fetch.php'),
				'filter=' + container.attr('data-activity-filter') + '&page=' + currentPage,
				function(data) {
					container.append(data);
					ignoreScroll = false;
					if (!data.length) {
						// Page is empty - No more activities :(
						$('#nomoreactivities').removeClass('hidden');
					}
				}
			);
		}
	}

	var container = $('#container'),
		content = $('#app-content'),
		currentPage = 0,
		ignoreScroll = false;
	processChildren(container);

	function prefill() {
		if (content.scrollTop() + content.height() > container.height() - 100) {
			currentPage++;

			//ignoreScroll = true;
			$.get(
				OC.filePath('activity', 'ajax', 'fetch.php'),
				'filter=' + container.attr('data-activity-filter') + '&page=' + currentPage,
				function(data) {
					container.append(data);
					if (data.length) {
						// Continue prefill
						prefill();
					}
					else if (currentPage == 1) {
						// First page is empty - No activities :(
						$('#noactivities').removeClass('hidden');
					}
					else {
						// Page is empty - No more activities :(
						$('#nomoreactivities').removeClass('hidden');
					}
				}
			);
		}
	}

	prefill();
	content.on('scroll', _onScroll);
});

