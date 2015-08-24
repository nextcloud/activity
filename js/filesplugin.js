/*
 * Copyright (c) 2015
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */

(function(OCA) {

var FilesPlugin = {
	attach: function(fileList) {
		fileList.registerTabView(new OCA.Activity.ActivityTabView());
	}
};

OC.Plugins.register('OCA.Files.FileList', FilesPlugin);

})(OCA);

