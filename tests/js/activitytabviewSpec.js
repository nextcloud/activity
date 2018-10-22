/*
 * Copyright (c) 2015
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */
describe('ActivityTabView', function() {
	var ActivityCollection = OCA.Activity.ActivityCollection;
	var ActivityTabView = OCA.Activity.ActivityTabView;

	describe('rendering', function() {
		var fetchStub, fileInfo, tabView;

		beforeEach(function() {
			fetchStub = sinon.stub(ActivityCollection.prototype, 'fetch');
			fileInfo = new OCA.Files.FileInfoModel({
				id: 123,
				name: 'test.txt'
			});
			tabView = new ActivityTabView();
			tabView.$container = tabView.$el.find('ul.activities');
		});
		afterEach(function() {
			fetchStub.restore();
			tabView.remove();
		});

		it('reloads matching activities when setting file info model', function() {
			tabView.setFileInfo(fileInfo);
			expect(fetchStub.calledOnce).toEqual(true);
			var url = OC.parseQueryString(tabView.collection.url());
			expect(url.object_id).toEqual('123');
			expect(url.object_type).toEqual('files');
		});

		it('renders loading icon while fetching activities', function() {
			tabView.setFileInfo(fileInfo);
			tabView.collection.trigger('request');

			expect(tabView.$el.find('.loading').length).toEqual(1);
			expect(tabView.$el.find('.activity').length).toEqual(0);
		});

		it('renders activities', function() {
			var activity1 = {
				subject: 'The Subject',
				subject_rich: [
					'The Subject {file1}',
					{
						file1: {
							id: 42,
							name: 'test.txt',
							path: 'foo/bar/test.txt'
						}
					}
				],
				datetime: moment().format(),
				message: 'Some message',
				message_rich: [
					'Some message',
					[]
				],
				message_prepared: 'Some <parameter class="markup">message</parameter>!',
				icon: OC.imagePath('core', 'actions/add.svg'),
				previews: [{
					isMimeTypeIcon: true,
					source: OC.imagePath('core', 'filetypes/text.svg')
				}, {
					isMimeTypeIcon: false,
					source: OC.imagePath('core', 'filetypes/text.svg')
				}]
			};
			var activity2 = {
				subject: 'The Subject Two',
				subject_rich: [
					'The Subject Two',
					[]
				],
				datetime: moment().subtract(1, 'y').format(),
				message: 'Two message',
				message_rich: [
					'Two message',
					[]
				]
			};
			tabView.setFileInfo(fileInfo);
			tabView.collection.trigger('request');
			tabView.collection.set([activity1, activity2]);

			var $activities = tabView.$container.find('.activity');
			expect($activities.length).toEqual(2);
			var $a1 = $activities.eq(0);
			expect($a1.find('.activitysubject').text()).toEqual('The Subject test.txt\n');
			expect($a1.find('.activitysubject strong').length).toEqual(1);
			expect($a1.find('.activitymessage').text()).toEqual('Some message');
			expect($a1.find('.activitymessage strong').length).toEqual(0);
			expect($a1.find('.activity-icon img').attr('src')).toContain('img/actions/add.svg');
			expect($a1.find('.activitytime').text()).toEqual('seconds ago');
			expect($a1.find('.activitytime').attr('data-original-title')).toContain(moment().format('MMMM'));
			expect($a1.find('.activitytime').attr('data-original-title')).toContain(moment().format('YYYY'));

			/*
			expect($a1.find('.previews img').length).toEqual(2);
			expect($a1.find('.previews img').eq(0).hasClass('preview-mimetype-icon')).toEqual(true);
			expect($a1.find('.previews img').eq(1).hasClass('preview-mimetype-icon')).toEqual(false);
			expect($a1.find('.previews img').eq(0).attr('src')).toEqual(OC.imagePath('core', 'filetypes/text.svg'));
			*/
			expect($a1.find('.previews').length).toEqual(0);

			var $a2 = $activities.eq(1);
			expect($a2.find('.previews').length).toEqual(0);
			expect($a2.find('.activitytime').attr('data-original-title')).toContain(moment().format('MMMM'));
			expect($a2.find('.activitytime').attr('data-original-title')).toContain(moment().format('YYYY') - 1);
		});
	});
});

