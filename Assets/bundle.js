/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(1);


/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	__webpack_require__(2);

	__webpack_require__(14);

/***/ },
/* 2 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _main = __webpack_require__(3);

	var _main2 = _interopRequireDefault(_main);

	var _view = __webpack_require__(11);

	var _view2 = _interopRequireDefault(_view);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	Vue.filter('advancedSort', function (arr, sortKey, reverse) {
	    var orderBy = Vue.filter('orderBy');
	    var orderdArrayKey = orderBy(arr, sortKey, reverse);
	    return orderBy(orderdArrayKey, 'tag', -1);
	});

	Vue.filter('quotaToMB', function (value) {
	    return value / 1024 / 1024;
	});

	Vue.filter('quotaToMB', {
	    read: function read(val) {
	        return val / 1024 / 1024;
	    },
	    write: function write(val, oldVal) {
	        return val * 1024 * 1024;
	    }
	});

	var App = Vue.extend(_main2.default);

	var View = Vue.extend(_view2.default);

	var router = new VueRouter();

	router.map({
	    '/:pool/*parent_uid': {
	        name: 'path',
	        component: View
	    }
	});

	router.start(App, '#societyAdmin');

/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	    value: true
	});

	var _pooltree = __webpack_require__(4);

	var _pooltree2 = _interopRequireDefault(_pooltree);

	var _breadcrumb = __webpack_require__(7);

	var _breadcrumb2 = _interopRequireDefault(_breadcrumb);

	var _upload = __webpack_require__(10);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = {
	    data: function data() {
	        return {
	            pools: [],
	            objects: [],
	            meta: null,
	            selectedPool: null,
	            selectedParent: null,

	            editObject: null,

	            newPool: {
	                title: null,
	                quota: 209715200,
	                readRoles: [],
	                writeRoles: []
	            },

	            uploadInProgress: false,
	            uploadProgress: 0,

	            showLoader: false
	        };
	    },

	    components: { pooltree: _pooltree2.default, breadcrumb: _breadcrumb2.default },
	    watch: {
	        '$route.params': function $routeParams() {
	            this.selectPool();
	        },
	        'selectedPool': function selectedPool() {
	            this.selectParent();
	            (0, _upload.dragAndDropModule)(this);
	        }

	    },
	    ready: function ready() {
	        this.requestPoolIndex();
	    },

	    methods: {
	        requestPoolIndex: function requestPoolIndex() {
	            var resource = this.$resource(resourceDocumentsPoolIndex);
	            resource.get({}).then(function (response) {
	                this.pools = response.data.data;
	                this.selectPool();
	            }.bind(this), function (response) {
	                toastr.error(response.data.message, 'Error: ' + response.data.status_code);
	            }.bind(this));
	        },
	        requestObjectIndex: function requestObjectIndex() {
	            if (this.selectedPool == null) {
	                return;
	            }

	            this.showLoader = true;

	            var resource = this.$resource(resourceDocumentsPoolListFolder);
	            resource.get({ uid: this.selectedPool.uid }, { parent_uid: this.selectedParent }).then(function (response) {
	                this.objects = response.data.data;
	                this.meta = response.data.meta;
	                this.showLoader = false;
	            }.bind(this), function (response) {
	                toastr.error(response.data.message, 'Error: ' + response.data.status_code);
	                this.showLoader = false;
	            }.bind(this));
	        },


	        redirectBack: function redirectBack() {
	            return this.$route.router.go(window.history.back());
	        },

	        redirectForward: function redirectForward() {
	            return this.$route.router.go(window.history.forward());
	        },

	        redirectUp: function redirectUp() {
	            return this.$route.router.go({
	                name: 'path',
	                params: { pool: this.selectedPool.uid, parent_uid: this.meta.parent_uid ? this.meta.parent_uid : 'null' }
	            });
	        },

	        selectPool: function selectPool() {

	            if (!this.$route.params || !this.$route.params.pool) {
	                return this.$route.router.go({
	                    name: 'path',
	                    params: { pool: this.pools[0].uid, parent_uid: 'null' }
	                });
	            }

	            var uid = this.$route.params.pool;
	            var result = $.grep(this.pools, function (e) {
	                return e.uid == uid;
	            });

	            if (result.length == 0) {
	                this.selectedPool = null;
	            } else if (result.length == 1) {
	                this.selectedPool = result[0];
	            } else {
	                this.selectedPool = result[0];
	            }
	        },
	        selectParent: function selectParent() {
	            if (this.$route.params && this.$route.params.parent_uid) {
	                this.selectedParent = this.$route.params.parent_uid;
	            }
	            this.requestObjectIndex();
	        },
	        createPoolModal: function createPoolModal() {
	            $('.ui.dropdown').dropdown();

	            $('#newPool').modal('setting', 'transition', 'fade up').modal('show');
	        },
	        permissionPoolModal: function permissionPoolModal() {
	            $('.ui.dropdown').dropdown();

	            $('#permissionPool').modal('setting', 'transition', 'fade up').modal('show');
	        },


	        createPool: function createPool() {
	            event.preventDefault();

	            var resource = this.$resource(resourceDocumentsPoolStore);
	            resource.save(this.newPool, function (data, status, request) {
	                var response = data.data;
	                this.pools.push(response);

	                $('#newPool').modal('hide');

	                this.newPool = {
	                    title: null,
	                    quota: 209715200,
	                    readRoles: [],
	                    writeRoles: []
	                };

	                return this.$route.router.go({
	                    name: 'path',
	                    params: { pool: response.uid, parent_uid: 'null' }
	                });
	            }.bind(this)).error(function (data, status, request) {
	                toastr.error(data.errors[0], data.message);
	            }.bind(this));
	        },

	        updatePool: function updatePool(pool) {
	            event.preventDefault();

	            var resource = this.$resource(resourceDocumentsPoolUpdate);
	            resource.update(pool, { uid: pool.uid }, function (data, status, request) {
	                var response = data.data;
	            }.bind(this)).error(function (data, status, request) {
	                toastr.error(data.errors[0], data.message);
	            }.bind(this));
	        },

	        fileUploadStart: function fileUploadStart() {
	            this.uploadInProgress = true;
	        },
	        fileUploadTotalProgress: function fileUploadTotalProgress(totalUploadedBytes, totalBytes) {
	            this.uploadProgress = Math.ceil(totalUploadedBytes / totalBytes * 100);
	            $('#uploadProgressBarTop, #uploadProgressBarBottom').progress({
	                percent: this.uploadProgress
	            });
	        },
	        fileUploadComplete: function fileUploadComplete(id, name, responseJSON) {
	            if (!responseJSON.success) {
	                return toastr.error(responseJSON.message);
	            }
	            if (responseJSON.data.uid) {
	                this.objects.push(responseJSON.data);
	                this.meta.objects.files++;
	                this.selectedPool.objects.files++;
	                this.selectedPool.quotaUsed += responseJSON.data.objectSize;
	            }
	        },
	        fileUploadAllComplete: function fileUploadAllComplete(responseJSON) {
	            this.uploadInProgress = false;
	            $('#uploadProgressBarTop, #uploadProgressBarBottom').progress({
	                percent: 0
	            });
	        },

	        createFolder: function createFolder(object, event) {
	            event.preventDefault();
	            var newFolder = {
	                mimeType: "application/x-directory",
	                parent_uid: this.selectedParent,
	                pool_uid: this.selectedPool.uid,
	                title: "New Folder"
	            };

	            var resource = this.$resource(resourceDocumentsFolderStore);
	            resource.save({ pool: this.selectedPool.uid }, newFolder, function (data, status, request) {

	                var index = this.objects.push(data.data);

	                this.objects[index - 1].editing = true;

	                this.meta.objects.folder++;
	                this.selectedPool.objects.folder++;
	            }.bind(this)).error(function (data, status, request) {
	                toastr.error(data.message);
	            }.bind(this));
	        }
	    }
	};

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(5)
	if (__vue_script__ &&
	    __vue_script__.__esModule &&
	    Object.keys(__vue_script__).length > 1) {
	  console.warn("[vue-loader] Resources/assets/js/components/pooltree.vue: named exports in *.vue files are ignored.")}
	__vue_template__ = __webpack_require__(6)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) {
	(typeof module.exports === "function" ? (module.exports.options || (module.exports.options = {})) : module.exports).template = __vue_template__
	}
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/home/ralph/webDev/societycms/modules/Documents/Resources/assets/js/components/pooltree.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 5 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.default = {
	    props: ['pool', 'selected']
	};

/***/ },
/* 6 */
/***/ function(module, exports) {

	module.exports = "\n<a v-link=\"{ name: 'path', params: { pool: pool.uid, parent_uid: 'null'}}\" v-bind:class=\"{'active': selected == pool}\" class=\"item\">\n    {{pool.title}}\n</a>\n";

/***/ },
/* 7 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_script__ = __webpack_require__(8)
	if (__vue_script__ &&
	    __vue_script__.__esModule &&
	    Object.keys(__vue_script__).length > 1) {
	  console.warn("[vue-loader] Resources/assets/js/components/breadcrumb.vue: named exports in *.vue files are ignored.")}
	__vue_template__ = __webpack_require__(9)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) {
	(typeof module.exports === "function" ? (module.exports.options || (module.exports.options = {})) : module.exports).template = __vue_template__
	}
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/home/ralph/webDev/societycms/modules/Documents/Resources/assets/js/components/breadcrumb.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 8 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.default = {
	    props: ['pool', 'meta'],
	    computed: {
	        containing_ns_path: function containing_ns_path() {
	            if (this.meta && this.meta.containing_ns_path && this.meta.containing_fq_uid) {

	                var currentPath = this.meta.containing_ns_path.split('/');
	                var currentPathUid = this.meta.containing_fq_uid.split(':');

	                var returnObject = [];

	                currentPath.forEach(function (element, index, array) {
	                    returnObject.push({
	                        'uid': currentPathUid[index],
	                        'title': element
	                    });
	                });

	                return returnObject;
	            }
	            return [];
	        }
	    }
	};

/***/ },
/* 9 */
/***/ function(module, exports) {

	module.exports = "\n\n<div class=\"ui breadcrumb item\">\n\n    <a class=\"section\" v-link=\"{ name: 'path', params: { pool: pool.uid, parent_uid: 'null'}}\"  v-if=\"pool\">\n        <i class=\"home icon\"></i>\n        {{ pool.title }}\n    </a>\n\n    <template v-for=\"item in containing_ns_path\">\n        <div class=\"divider\"> / </div>\n        <a class=\"ui section text\" v-link=\"{ name: 'path', params: { pool: pool.uid, parent_uid: item.uid}}\">{{ item.title }}</a>\n    </template>\n\n    <div class=\"divider\"> / </div>\n\n</div>\n";

/***/ },
/* 10 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.dragAndDropModule = dragAndDropModule;
	exports.fineUploaderBasicInstance = fineUploaderBasicInstance;
	function dragAndDropModule(VueInstance) {

	    var uploaderInstance = fineUploaderBasicInstance(VueInstance);

	    return new fineUploader.DragAndDrop({
	        dropZoneElements: [document.getElementById('fileView')],
	        classes: {
	            dropActive: 'blue'
	        },
	        callbacks: {
	            processingDroppedFilesComplete: function processingDroppedFilesComplete(files, dropTarget) {
	                uploaderInstance.addFiles(files);
	            }
	        }
	    });
	};

	function fineUploaderBasicInstance(VueInstance) {

	    return new fineUploader.FineUploaderBasic({
	        button: document.getElementById('uploadFileButton'),
	        request: {
	            endpoint: Vue.url(resourceDocumentsFileStore, { pool: VueInstance.$route.params.pool }),
	            inputName: 'data-binary',
	            customHeaders: {
	                "Authorization": "Bearer " + societycms.jwtoken
	            },
	            params: {
	                parent_uid: VueInstance.selectedParent
	            }
	        },
	        callbacks: {

	            onComplete: function onComplete(id, name, responseJSON) {
	                VueInstance.fileUploadComplete(id, name, responseJSON);
	            },
	            onError: function onError(id, name, errorReason, XMLHttpRequest) {
	                var responseJSON = JSON.parse(XMLHttpRequest.response);

	                if (responseJSON.errors) {
	                    toastr.error(responseJSON.errors[0], responseJSON.message);
	                    this.editMode = null;
	                    this.editObject = null;
	                }
	            },
	            onUpload: function onUpload() {
	                VueInstance.fileUploadStart();
	            },
	            onTotalProgress: function onTotalProgress(totalUploadedBytes, totalBytes) {
	                VueInstance.fileUploadTotalProgress(totalUploadedBytes, totalBytes);
	            },
	            onAllComplete: function onAllComplete(succeeded, failed) {
	                VueInstance.fileUploadAllComplete(succeeded, failed);
	            }
	        }
	    });
	};

/***/ },
/* 11 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	    value: true
	});

	var _list = __webpack_require__(12);

	var _list2 = _interopRequireDefault(_list);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = {
	    data: function data() {
	        return {
	            sortKey: 'title',
	            sortReverse: 1,

	            editObject: null

	        };
	    },

	    template: _list2.default.template,
	    props: ['pool', 'objects'],
	    watch: {
	        'objects': function objects() {
	            if (this.objects[this.objects.length - 1] && this.objects[this.objects.length - 1].editing == true) {
	                this.objects[this.objects.length - 1].editing = false;
	                this.objectEdit(this.objects[this.objects.length - 1]);
	            }

	            this.$nextTick(function () {
	                $('.ui.dropdown').dropdown();
	            });
	        }
	    },
	    methods: {
	        'sortBy': function sortBy(sortKey) {
	            this.sortReverse = this.sortKey == sortKey ? this.sortReverse * -1 : 1;
	            this.sortKey = sortKey;
	        },
	        objectOpen: function objectOpen(object, event) {
	            event.preventDefault();

	            if (object.tag == 'folder') {
	                return this.$route.router.go({
	                    name: 'path',
	                    params: { pool: this.pool.uid, parent_uid: object.uid }
	                });
	            }

	            return window.open(object.downloadUrl + '?token=' + societycms.jwtoken, "_blank");
	        },

	        objectEdit: function objectEdit(object, event) {
	            this.editObject = object;
	            this.editMode = 'rename';

	            setTimeout(function () {
	                $('#objectEditInput-' + object.uid).focus();
	                $('#objectEditInput-' + object.uid).select();
	            }, 50);
	        },
	        objectBlurEdit: function objectBlurEdit(object, event) {
	            event.preventDefault();

	            if (this.editObject == null || this.editMode != 'rename') {
	                return;
	            }

	            var resource = this.$resource(resourceDocumentsFileUpdate);
	            resource.update({ pool: this.pool.uid }, this.editObject, function (data, status, request) {
	                this.editMode = null;
	                this.editObject = null;
	            }.bind(this)).error(function (data, status, request) {
	                toastr.error(data.errors[0], 'Error: ' + data.message);
	            });
	        },
	        objectKeydownEdit: function objectKeydownEdit(object, event) {
	            if (event.which == 13) {
	                this.objectBlurEdit(object, event);
	            }
	        },

	        objectDelete: function objectDelete(object, event) {
	            event.preventDefault();

	            var resource = this.$resource(resourceDocumentsFileDestroy);
	            resource.delete({ pool: this.pool.uid }, object, function (data, status, request) {
	                if (status) {
	                    if (this.showDeleted) {
	                        object.deleted = true;
	                    } else {
	                        this.objects.$remove(object);
	                    }

	                    if (object.tag == 'file') {
	                        this.pool.objects.files--;
	                    }
	                }
	            }.bind(this)).error(function (data, status, request) {
	                toastr.error(data.errors[0], 'Error: ' + data.message);
	            });
	        }
	    }
	};

/***/ },
/* 12 */
/***/ function(module, exports, __webpack_require__) {

	var __vue_script__, __vue_template__
	__vue_template__ = __webpack_require__(13)
	module.exports = __vue_script__ || {}
	if (module.exports.__esModule) module.exports = module.exports.default
	if (__vue_template__) {
	(typeof module.exports === "function" ? (module.exports.options || (module.exports.options = {})) : module.exports).template = __vue_template__
	}
	if (false) {(function () {  module.hot.accept()
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), true)
	  if (!hotAPI.compatible) return
	  var id = "/home/ralph/webDev/societycms/modules/Documents/Resources/assets/js/components/list.vue"
	  if (!module.hot.data) {
	    hotAPI.createRecord(id, module.exports)
	  } else {
	    hotAPI.update(id, module.exports, __vue_template__)
	  }
	})()}

/***/ },
/* 13 */
/***/ function(module, exports) {

	module.exports = "\n\n<table class=\"ui selectable table\" id=\"file-list-table\">\n    <thead>\n    <tr>\n        <th class=\"therteen wide filename\"\n            v-on:click=\"sortBy('title')\"\n            v-bind:class=\"{ 'sorted': sortKey == 'tag', 'ascending':sortReverse>0, 'descending':sortReverse<0}\">\n            Title\n        </th>\n        <th class=\"\">\n        </th>\n        <th class=\"one wide right aligned\"\n            v-on:click=\"sortBy('objectSize')\"\n            v-bind:class=\"{ 'sorted': sortKey == 'objectSize', 'ascending':sortReverse>0, 'descending':sortReverse<0}\">\n            Size\n        </th>\n        <th class=\"two wide right aligned\"\n            v-on:click=\"sortBy('created_at.timestamp')\"\n            v-bind:class=\"{ 'sorted': sortKey == 'created_at.timestamp', 'ascending':sortReverse>0, 'descending':sortReverse<0}\">\n            Modified\n        </th>\n    </tr>\n    </thead>\n    <tbody>\n\n    <tr class=\"object\" v-bind:class=\"{'negative':object.deleted}\" v-for=\"object in objects | filterBy filterKey | advancedSort sortKey sortReverse\">\n        <td class=\"selectable\">\n            <a v-if=\"editObject != object\" href=\"\" v-on:click=\"objectOpen(object, $event)\">\n                <i v-bind:class=\"object.mimeType | semanticFileTypeClass\" class=\"icon\"></i>\n\n                <div class=\"ui text\">{{ object.title }}\n                    <span class=\"ui gray text\"\n                        v-if=\"object.fileExtension\">.{{ object.fileExtension }}\n                    </span>\n                </div>\n\n            </a>\n\n            <div class=\"ui right action left icon input\" v-else>\n                <i v-bind:class=\"object.mimeType | semanticFileTypeClass\" class=\"icon\"></i>\n                <input type=\"text\" id=\"objectEditInput-{{object.uid}}\"\n                       v-model=\"object.title\"\n                       v-on:blur=\"objectBlurEdit(object, $event)\"\n                       v-on:keydown=\"objectKeydownEdit(object, $event)\" >\n                <div class=\"ui icon primary button\" v-on:click=\"objectBlurEdit(object, $event)\">\n                    <i class=\"checkmark icon\"></i>\n                </div>\n            </div>\n        </td>\n        <td class=\"collapsing\">\n\n            <button class=\"circular ui icon positive button\" v-if=\"object.deleted\" v-on:click=\"objectRestore(object, $event)\"><i class=\"life ring icon\"></i></button>\n            <button class=\"circular ui icon negative button\" v-if=\"object.deleted\" v-on:click=\"objectForceDelete(object, $event)\"><i class=\"trash icon\"></i></button>\n\n            <button class=\"circular ui icon disabled button\" v-if=\"!object.deleted\"><i class=\"share alternate icon \"></i></button>\n\n            <div class=\"ui top left pointing dropdown\" v-if=\"!object.deleted\">\n                <button class=\"circular ui icon button\"><i class=\"ellipsis horizontal icon\"></i></button>\n\n                <div class=\"menu\">\n                    <div class=\"item\" v-on:click=\"objectOpen(object, $event)\">\n                        Open...\n                    </div>\n                    <div class=\"item\" v-on:click=\"objectEdit(object, $event)\">\n                        Rename\n                    </div>\n                    <div class=\"item\" v-on:click=\"objectDelete(object, $event)\">\n                        <i class=\"trash icon\"></i>\n                        Move to trash\n                    </div>\n                </div>\n            </div>\n\n        </td>\n        <td class=\"right aligned collapsing\" v-if=\"object.tag == 'file'\">{{ object.objectSize | humanReadableFilesize }}</td>\n        <td class=\"right aligned collapsing\" v-if=\"object.tag == 'folder'\">-</td>\n        <td class=\"right aligned collapsing\">{{ object.created_at.diffForHumans }}</td>\n    </tr>\n    </tbody>\n</table>\n\n";

/***/ },
/* 14 */
/***/ function(module, exports, __webpack_require__) {

	// style-loader: Adds some css to the DOM by adding a <style> tag

	// load the styles
	var content = __webpack_require__(15);
	if(typeof content === 'string') content = [[module.id, content, '']];
	// add the styles to the DOM
	var update = __webpack_require__(17)(content, {});
	if(content.locals) module.exports = content.locals;
	// Hot Module Replacement
	if(false) {
		// When the styles change, update the <style> tags
		if(!content.locals) {
			module.hot.accept("!!./../../../node_modules/css-loader/index.js!./../../../node_modules/postcss-loader/index.js!./Documents.scss", function() {
				var newContent = require("!!./../../../node_modules/css-loader/index.js!./../../../node_modules/postcss-loader/index.js!./Documents.scss");
				if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
				update(newContent);
			});
		}
		// When the module is disposed, remove the <style> tags
		module.hot.dispose(function() { update(); });
	}

/***/ },
/* 15 */
/***/ function(module, exports, __webpack_require__) {

	exports = module.exports = __webpack_require__(16)();
	// imports


	// module
	exports.push([module.id, "/*******************************\n         Site Settings\n*******************************/\n/*-------------------\n       Fonts\n--------------------*/\n/*-------------------\n      Base Sizes\n--------------------*/\n/* This is the single variable that controls them all */\n/* The size of page text  */\n/*-------------------\n    Border Radius\n--------------------*/\n/* See Power-user section below\n   for explanation of $px variables\n*/\n/*-------------------\n    Brand Colors\n--------------------*/\n/*--------------\n  Page Heading\n---------------*/\n/*--------------\n   Form Input\n---------------*/\n/* This adjusts the default form input across all elements */\n/* Line Height Default For Inputs in Browser */\n/*-------------------\n    Focused Input\n--------------------*/\n/* Used on inputs, textarea etc */\n/* Used on dropdowns, other larger blocks */\n/*-------------------\n        Sizes\n--------------------*/\n/*\n  Sizes are all expressed in terms of 14px/em (default em)\n  This ensures these \"ratios\" remain constant despite changes in EM\n*/\n/*-------------------\n        Page\n--------------------*/\n/*-------------------\n      Paragraph\n--------------------*/\n/*-------------------\n       Links\n--------------------*/\n/*-------------------\n  Highlighted Text\n--------------------*/\n/*-------------------\n       Loader\n--------------------*/\n/*-------------------\n        Grid\n--------------------*/\n/*-------------------\n     Transitions\n--------------------*/\n/*-------------------\n     Breakpoints\n--------------------*/\n/*-------------------\n      Site Colors\n--------------------*/\n/*---  Colors  ---*/\n/*---  Light Colors  ---*/\n/*---   Neutrals  ---*/\n/*--- Colored Backgrounds ---*/\n/*--- Colored Headers ---*/\n/*--- Colored Text ---*/\n//: #8ABC1E;\n//: #1EBC30;\n//: #10A3A3;\n//: #2185D0;\n/*-------------------\n     Alpha Colors\n--------------------*/\n/*-------------------\n       Accents\n--------------------*/\n/* Differentiating Neutrals */\n/* Differentiating Layers */\n/*******************************\n           Power-User\n*******************************/\n/*-------------------\n    Emotive Colors\n--------------------*/\n/* Positive */\n/* Negative */\n/* Info */\n/* Warning */\n/*-------------------\n        Paths\n--------------------*/\n/* For source only. Modified in gulp for dist */\n/*-------------------\n       Em Sizes\n--------------------*/\n/*\n  This rounds $size values to the closest pixel then expresses that value in (r)em.\n  This ensures all size values round to exact pixels\n*/\n/* em */\n/* rem */\n/*-------------------\n       Icons\n--------------------*/\n/* Maximum Glyph Width of Icon */\n/*-------------------\n     Neutral Text\n--------------------*/\n/*-------------------\n     Brand Colors\n--------------------*/\n/*-------------------\n      Borders\n--------------------*/\n/*-------------------\n    Derived Values\n--------------------*/\n/* Loaders Position Offset */\n/* Rendered Scrollbar Width */\n/* Maximum Single Character Glyph Width, aka Capital \"W\" */\n/* Used to match floats with text */\n/* Header Spacing */\n/* Minimum Mobile Width */\n/* Positive / Negative Dupes */\n/* Responsive */\n/* Columns */\n/*******************************\n             States\n*******************************/\n/*-------------------\n      Disabled\n--------------------*/\n/*-------------------\n        Hover\n--------------------*/\n/*---  Colors  ---*/\n/*---  Emotive  ---*/\n/*---  Brand   ---*/\n/*---  Dark Tones  ---*/\n/*---  Light Tones  ---*/\n/*-------------------\n        Focus\n--------------------*/\n/*---  Colors  ---*/\n/*---  Emotive  ---*/\n/*---  Brand   ---*/\n/*---  Dark Tones  ---*/\n/*---  Light Tones  ---*/\n/*-------------------\n    Down (:active)\n--------------------*/\n/*---  Colors  ---*/\n/*---  Emotive  ---*/\n/*---  Brand   ---*/\n/*---  Dark Tones  ---*/\n/*---  Light Tones  ---*/\n/*-------------------\n        Active\n--------------------*/\n/*---  Colors  ---*/\n/*---  Emotive  ---*/\n/*---  Brand   ---*/\n/*---  Dark Tones  ---*/\n/*---  Light Tones  ---*/\n\n#fileBrowser {\n  padding: 0;\n\n}\n\n#fileBrowser .container {\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n\n}\n\n#fileBrowser .treeView {\n  padding: 0;\n  margin: 0 1em 0 0;\n\n}\n\n#fileBrowser .fileView {\n  padding: 0;\n  margin: 0;\n  min-height: 20em;\n  width: 100%;\n\n}\n\n#fileBrowser .fileView .ui.table {\n  margin-top: 0;\n\n}\n\n\n.ui.menu.fileMenu .item>.button {\n  margin: -.5em .25em;\n\n}\n\n\n.ui.menu.fileMenu .ui.breadcrumb.item:before {\n  display: none;\n\n}\n\n#file-list-table {\n  border:none;\n  border-radius:0;\n}\n\n#file-list-table tbody tr td.selectable:hover {\n  background: none!important;\n  color: rgba(0,0,0,.95)!important;\n\n}\n\n#file-list-table tbody tr td.selectable>.ui.input {\n  padding: .71428571em;\n\n}\n\n#file-list-table .ui[class*=\"left icon\"].input>input {\n  padding-left: 1.5em!important;\n\n}\n\n#file-list-table .object .ui.text {\n  display: -webkit-inline-box;\n  display: -webkit-inline-flex;\n  display: -ms-inline-flexbox;\n  display: inline-flex;\n\n}\n\n#file-list-table .object .ui.input {\n  min-width: 50%;\n\n}\n", ""]);

	// exports


/***/ },
/* 16 */
/***/ function(module, exports) {

	/*
		MIT License http://www.opensource.org/licenses/mit-license.php
		Author Tobias Koppers @sokra
	*/
	// css base code, injected by the css-loader
	module.exports = function() {
		var list = [];

		// return the list of modules as css string
		list.toString = function toString() {
			var result = [];
			for(var i = 0; i < this.length; i++) {
				var item = this[i];
				if(item[2]) {
					result.push("@media " + item[2] + "{" + item[1] + "}");
				} else {
					result.push(item[1]);
				}
			}
			return result.join("");
		};

		// import a list of modules into the list
		list.i = function(modules, mediaQuery) {
			if(typeof modules === "string")
				modules = [[null, modules, ""]];
			var alreadyImportedModules = {};
			for(var i = 0; i < this.length; i++) {
				var id = this[i][0];
				if(typeof id === "number")
					alreadyImportedModules[id] = true;
			}
			for(i = 0; i < modules.length; i++) {
				var item = modules[i];
				// skip already imported module
				// this implementation is not 100% perfect for weird media query combinations
				//  when a module is imported multiple times with different media queries.
				//  I hope this will never occur (Hey this way we have smaller bundles)
				if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
					if(mediaQuery && !item[2]) {
						item[2] = mediaQuery;
					} else if(mediaQuery) {
						item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
					}
					list.push(item);
				}
			}
		};
		return list;
	};


/***/ },
/* 17 */
/***/ function(module, exports, __webpack_require__) {

	/*
		MIT License http://www.opensource.org/licenses/mit-license.php
		Author Tobias Koppers @sokra
	*/
	var stylesInDom = {},
		memoize = function(fn) {
			var memo;
			return function () {
				if (typeof memo === "undefined") memo = fn.apply(this, arguments);
				return memo;
			};
		},
		isOldIE = memoize(function() {
			return /msie [6-9]\b/.test(window.navigator.userAgent.toLowerCase());
		}),
		getHeadElement = memoize(function () {
			return document.head || document.getElementsByTagName("head")[0];
		}),
		singletonElement = null,
		singletonCounter = 0,
		styleElementsInsertedAtTop = [];

	module.exports = function(list, options) {
		if(false) {
			if(typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
		}

		options = options || {};
		// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
		// tags it will allow on a page
		if (typeof options.singleton === "undefined") options.singleton = isOldIE();

		// By default, add <style> tags to the bottom of <head>.
		if (typeof options.insertAt === "undefined") options.insertAt = "bottom";

		var styles = listToStyles(list);
		addStylesToDom(styles, options);

		return function update(newList) {
			var mayRemove = [];
			for(var i = 0; i < styles.length; i++) {
				var item = styles[i];
				var domStyle = stylesInDom[item.id];
				domStyle.refs--;
				mayRemove.push(domStyle);
			}
			if(newList) {
				var newStyles = listToStyles(newList);
				addStylesToDom(newStyles, options);
			}
			for(var i = 0; i < mayRemove.length; i++) {
				var domStyle = mayRemove[i];
				if(domStyle.refs === 0) {
					for(var j = 0; j < domStyle.parts.length; j++)
						domStyle.parts[j]();
					delete stylesInDom[domStyle.id];
				}
			}
		};
	}

	function addStylesToDom(styles, options) {
		for(var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];
			if(domStyle) {
				domStyle.refs++;
				for(var j = 0; j < domStyle.parts.length; j++) {
					domStyle.parts[j](item.parts[j]);
				}
				for(; j < item.parts.length; j++) {
					domStyle.parts.push(addStyle(item.parts[j], options));
				}
			} else {
				var parts = [];
				for(var j = 0; j < item.parts.length; j++) {
					parts.push(addStyle(item.parts[j], options));
				}
				stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
			}
		}
	}

	function listToStyles(list) {
		var styles = [];
		var newStyles = {};
		for(var i = 0; i < list.length; i++) {
			var item = list[i];
			var id = item[0];
			var css = item[1];
			var media = item[2];
			var sourceMap = item[3];
			var part = {css: css, media: media, sourceMap: sourceMap};
			if(!newStyles[id])
				styles.push(newStyles[id] = {id: id, parts: [part]});
			else
				newStyles[id].parts.push(part);
		}
		return styles;
	}

	function insertStyleElement(options, styleElement) {
		var head = getHeadElement();
		var lastStyleElementInsertedAtTop = styleElementsInsertedAtTop[styleElementsInsertedAtTop.length - 1];
		if (options.insertAt === "top") {
			if(!lastStyleElementInsertedAtTop) {
				head.insertBefore(styleElement, head.firstChild);
			} else if(lastStyleElementInsertedAtTop.nextSibling) {
				head.insertBefore(styleElement, lastStyleElementInsertedAtTop.nextSibling);
			} else {
				head.appendChild(styleElement);
			}
			styleElementsInsertedAtTop.push(styleElement);
		} else if (options.insertAt === "bottom") {
			head.appendChild(styleElement);
		} else {
			throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");
		}
	}

	function removeStyleElement(styleElement) {
		styleElement.parentNode.removeChild(styleElement);
		var idx = styleElementsInsertedAtTop.indexOf(styleElement);
		if(idx >= 0) {
			styleElementsInsertedAtTop.splice(idx, 1);
		}
	}

	function createStyleElement(options) {
		var styleElement = document.createElement("style");
		styleElement.type = "text/css";
		insertStyleElement(options, styleElement);
		return styleElement;
	}

	function createLinkElement(options) {
		var linkElement = document.createElement("link");
		linkElement.rel = "stylesheet";
		insertStyleElement(options, linkElement);
		return linkElement;
	}

	function addStyle(obj, options) {
		var styleElement, update, remove;

		if (options.singleton) {
			var styleIndex = singletonCounter++;
			styleElement = singletonElement || (singletonElement = createStyleElement(options));
			update = applyToSingletonTag.bind(null, styleElement, styleIndex, false);
			remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true);
		} else if(obj.sourceMap &&
			typeof URL === "function" &&
			typeof URL.createObjectURL === "function" &&
			typeof URL.revokeObjectURL === "function" &&
			typeof Blob === "function" &&
			typeof btoa === "function") {
			styleElement = createLinkElement(options);
			update = updateLink.bind(null, styleElement);
			remove = function() {
				removeStyleElement(styleElement);
				if(styleElement.href)
					URL.revokeObjectURL(styleElement.href);
			};
		} else {
			styleElement = createStyleElement(options);
			update = applyToTag.bind(null, styleElement);
			remove = function() {
				removeStyleElement(styleElement);
			};
		}

		update(obj);

		return function updateStyle(newObj) {
			if(newObj) {
				if(newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap)
					return;
				update(obj = newObj);
			} else {
				remove();
			}
		};
	}

	var replaceText = (function () {
		var textStore = [];

		return function (index, replacement) {
			textStore[index] = replacement;
			return textStore.filter(Boolean).join('\n');
		};
	})();

	function applyToSingletonTag(styleElement, index, remove, obj) {
		var css = remove ? "" : obj.css;

		if (styleElement.styleSheet) {
			styleElement.styleSheet.cssText = replaceText(index, css);
		} else {
			var cssNode = document.createTextNode(css);
			var childNodes = styleElement.childNodes;
			if (childNodes[index]) styleElement.removeChild(childNodes[index]);
			if (childNodes.length) {
				styleElement.insertBefore(cssNode, childNodes[index]);
			} else {
				styleElement.appendChild(cssNode);
			}
		}
	}

	function applyToTag(styleElement, obj) {
		var css = obj.css;
		var media = obj.media;

		if(media) {
			styleElement.setAttribute("media", media)
		}

		if(styleElement.styleSheet) {
			styleElement.styleSheet.cssText = css;
		} else {
			while(styleElement.firstChild) {
				styleElement.removeChild(styleElement.firstChild);
			}
			styleElement.appendChild(document.createTextNode(css));
		}
	}

	function updateLink(linkElement, obj) {
		var css = obj.css;
		var sourceMap = obj.sourceMap;

		if(sourceMap) {
			// http://stackoverflow.com/a/26603875
			css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
		}

		var blob = new Blob([css], { type: "text/css" });

		var oldSrc = linkElement.href;

		linkElement.href = URL.createObjectURL(blob);

		if(oldSrc)
			URL.revokeObjectURL(oldSrc);
	}


/***/ }
/******/ ]);