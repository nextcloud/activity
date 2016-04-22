/*
 * Copyright (c) 2015
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */
describe('ActivityCollection', function() {
	var ActivityCollection = OCA.Activity.ActivityCollection;

	describe('query url', function() {
		it('does not filter by default', function() {
			var col = new ActivityCollection();
			expect(col.url())
				.toEqual(
					//OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filter?format=json'
					OC.generateUrl('/apps/activity/api/v2/activity') + '/filter?format=json'
				);
		});
		it('does not filter by default - page2', function() {
			var col = new ActivityCollection();
			col.lastGivenId = 23;
			expect(col.url())
				.toEqual(
					//OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filter?format=json'
					OC.generateUrl('/apps/activity/api/v2/activity') + '/filter?format=json&since=23'
				);
		});
		it('filters by id and type when specified', function() {
			var col = new ActivityCollection();
			col.setObjectType('files');
			col.setObjectId(512);
			expect(col.url())
				.toEqual(
					//OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filter' +
					OC.generateUrl('/apps/activity/api/v2/activity') + '/filter' +
					'?format=json&object_type=files&object_id=512'
				);
		});
		it('filters by id and type when specified - page2', function() {
			var col = new ActivityCollection();
			col.setObjectType('files');
			col.setObjectId(512);
			col.lastGivenId = 42;
			expect(col.url())
				.toEqual(
					//OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filter' +
					OC.generateUrl('/apps/activity/api/v2/activity') + '/filter' +
					'?format=json&since=42&object_type=files&object_id=512'
				);
		});
	});
});

