@extends('layouts.master')

@section('title')
    {{ trans('documents::documents.title.documents') }}
@endsection

@section('content')

    <div class="ui breadcrumb filepath" v-show="currentPool">
        <a class="section" v-on:click="currentFolder = nul">
            <i class="home icon"></i>
            @{{ currentPool.title }}
        </a>

        <template v-for="item in containing_ns_path">
            <i class="right angle icon divider"></i>
            <a class="ui section text" v-on:click="breadcrumbClick(item.uid)"
               v-bind:class="{ 'black': currentFolder == item.uid }">@{{ item.title }}</a>
        </template>


        <i class="right angle icon divider"></i>

        <div class="ui icon top left pointing dropdown button" v-bind:class="{'active':editMode == 'createFolder'}">
            <i class="plus icon"></i>

            <div class="menu">
                <a class="item" id="uploadFileButton">Upload</a>
                <div class="item" v-on:click="createFolder(object, $event)">
                    <div class="ui text">Folder</div>
                </div>
            </div>
        </div>

    </div>

    <div class="ui divider"></div>


    <div class="ui grid">
        <div class="four wide column">

            <div class="ui fluid vertical pointing menu filepools">

                <a class="item" v-for="pool in pools" v-on:click="poolClick(pool);"
                   v-bind:class="{ 'active': currentPool.uid == pool.uid }">
                    <i class="large home middle aligned icon"></i>

                    <div class="ui label"
                         v-bind:class="{ 'blue': currentPool.uid == pool.uid }"> @{{ pool.objects.files }}</div>
                    @{{ pool.title }}
                </a>

                <div class="clearing item">
                    <button class="ui right floated toggle icon button" v-bind:class="{'active':showDeleted}" v-on:click="showDeleted=!showDeleted">
                        <i class="trash outline icon"></i>
                    </button>
                    <button class="ui basic button">
                        <i class="settings icon"></i>
                        Settings
                    </button>
                </div>

            </div>

        </div>
        <div v-bind:class="{ 'twelve': pools.length > 1, 'wide': pools.length > 1, 'column': pools.length > 1, 'column': pools.length = 1}">

            <div class="ui progress" id="uploadFileProgrssbar" v-if="editMode == 'uploadFiles'">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">Uploading Files</div>
            </div>


            <table class="ui sortable selectable table" id="file-list-table">
                <thead>
                <tr>
                    <th class="therteen wide filename"
                        v-on:click="sortBy('title')"
                        v-bind:class="{ 'sorted': sortKey == 'tag', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                        Name
                    </th>
                    <th class="one wide">
                    </th>
                    <th class="one wide right aligned"
                        v-on:click="sortBy('objectSize')"
                        v-bind:class="{ 'sorted': sortKey == 'objectSize', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                        Size
                    </th>
                    <th class="two wide right aligned"
                        v-on:click="sortBy('created_at.timestamp')"
                        v-bind:class="{ 'sorted': sortKey == 'created_at.timestamp', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                        Modified
                    </th>
                </tr>
                </thead>
                <tbody>

                <tr class="object" v-if="editMode == 'createFolder'">
                    <td>
                        <div class="ui input">
                            <input type="text" id="createFolderInput"
                                   v-model="editObject.title" v-on:blur="folderBlurCreate(editObject, $event)"
                                   v-on:keydown="folderKeydownCreate(editObject, $event)">
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr class="object" v-bind:class="{'negative':object.deleted}"   v-for="object in list_folder
                                                                                    | filterBy filterKey
                                                                                    | advancedSort sortKey sortReverse">

                    <td class="selectable">
                        <a href="#"
                           v-on:click="objectOpen(object, $event)"><i
                                    v-bind:class="object.mimeType | semanticFileTypeClass"
                                    class="icon"></i>

                            <div class="ui text" v-if="editObject != object">@{{ object.title }} <span
                                        class="ui gray text"
                                        v-if="object.fileExtension">.@{{ object.fileExtension }}</span>
                            </div>
                            <div class="ui input" v-else>
                                <input type="text" v-model="object.title" v-on:blur="objectBlurEdit(object, $event)"
                                       v-on:keydown="objectKeydownEdit(object, $event)" id="objectEditInput-@{{object.uid}}">
                            </div>

                        </a>
                    </td>
                    <td class="collapsing">

                        <button class="circular ui icon positive button" v-if="object.deleted"><i class="life ring icon"></i></button>
                        <button class="circular ui icon negative button" v-if="object.deleted"><i class="trash icon"></i></button>


                        <button class="circular ui icon button" v-if="!object.deleted"><i class="share alternate icon"></i></button>

                        <div class="ui top left pointing dropdown" v-if="!object.deleted">
                            <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>

                            <div class="menu">
                                <div class="item" v-on:click="objectOpen(object, $event)">
                                    Open...
                                </div>
                                <div class="item" v-on:click="objectEdit(object, $event)">
                                    Rename
                                </div>
                                <div class="item">
                                    <i class="folder icon"></i>
                                    Move to folder
                                </div>
                                <div class="item" v-on:click="objectDelete(object, $event)">
                                    <i class="trash icon"></i>
                                    Move to trash
                                </div>
                            </div>
                        </div>

                    </td>
                    <td class="right aligned collapsing"
                        v-text="object.objectSize | humanReadableFilesize"></td>
                    <td class="right aligned collapsing">@{{ object.created_at.diffForHumans }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th><span v-if="folder_meta">@{{ folder_meta.objects.folders }}
                            folders and @{{ folder_meta.objects.files }} files</span></th>
                    <th></th>
                    <th class="right aligned collapsing"></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

            <div class="ui active inverted dimmer" v-if="!list_folder && typeof currentPool != 'undefined'">
                <div class="ui medium text loader">Loading</div>
            </div>

        </div>
    </div>
@endsection

@section('styles')
    <link href="{{\Pingpong\Modules\Facades\Module::asset('documents:css/Documents.css')}}" rel="stylesheet"
          type="text/css">
@endsection

@section('javascript')
    <script src="{{\Pingpong\Modules\Facades\Module::asset('documents:js/app.js')}}"></script>
    <script>

        function initializeComponents() {
            setTimeout(function () {

                $('.ui.dropdown')
                        .dropdown();

            }, 100);
        }

        function isObject(obj) {
            return obj !== null && typeof obj === 'object'
        }

        function getPath(obj, path) {
            return parseExpression(path).get(obj)
        }

        Vue.filter('advancedSort', function (arr, sortKey, reverse) {
            var orderBy = Vue.filter('orderBy');
            var orderdArrayKey = orderBy(arr, sortKey, reverse);
            return orderBy(orderdArrayKey, 'tag', -1);
        });


        var VueInstance = new Vue({
            el: '#content',
            data: {
                currentPool: null,
                currentFolder: null,
                pools: [],
                list_folder: null,
                folder_meta: null,
                editObject: null,
                editMode: null,
                sortKey: '',
                sortReverse: 1,
                showDeleted:false,
            },
            ready: function () {

                var resource = this.$resource('{{apiRoute('v1', 'api.documents.pool.index')}}');
                // save item
                resource.get({}).then(function (response) {
                    this.pools = response.data.data;
                    this.currentPool = response.data.data[0];
                    this.setFromURL();
                }.bind(this), function (response) {
                    toastr.error(response.data.message, 'Error: ' + response.data.status_code);
                }.bind(this));

            },
            watch: {
                'currentFolder': function (val, oldVal) {
                    this.listFolder()
                },
                'showDeleted': function (val, oldVal) {
                    this.listFolder()
                }
            },
            computed: {
                containing_ns_path: function () {
                    if (
                            this.currentFolder &&
                            this.folder_meta &&
                            this.folder_meta.containing_ns_path &&
                            this.folder_meta.containing_fq_uid

                    ) {

                        currentPath = this.folder_meta.containing_ns_path.split('/');
                        currentPathUid = this.folder_meta.containing_fq_uid.split(':');

                        returnObject = [];

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
            },
            methods: {
                sortBy: function (sortKey) {
                    this.sortReverse = (this.sortKey == sortKey) ? this.sortReverse * -1 : 1;
                    this.sortKey = sortKey;
                },
                poolClick: function (pool) {
                    if (this.currentPool != pool) {
                        this.currentPool = pool;
                    }
                    if (this.currentFolder == null) {
                        return this.listFolder()
                    }
                    return this.currentFolder = null
                },
                objectOpen: function (object, event) {
                    event.preventDefault()

                    if (this.editObject == object) {
                        return;
                    }
                    if (object.tag == 'folder') {
                        return this.currentFolder = object.uid
                    }

                    return window.open(object.downloadUrl + '?token={{$jwtoken}}',"_blank")
                },
                breadcrumbClick: function (uid) {
                    return this.currentFolder = uid
                },
                setFromURL: function () {
                    var path = window.location.hash,
                            currentPathUid = null;

                    path = path.replace(/\/$/, "");
                    path = path.replace('#', '');
                    path = decodeURIComponent(path);

                    currentPathUid = path.split(':');

                    var result = $.grep(this.pools, function (e) {
                        return e.uid == currentPathUid[0];
                    });

                    if (result.length == 1) {
                        this.currentPool = result[0];
                        this.currentFolder = currentPathUid[1];
                    } else {
                        this.listFolder()
                    }

                },
                listFolder: function () {

                    this.editMode = null;
                    this.editObject = null;

                    if (!this.currentPool) {
                        return;
                    }

                    this.list_folder = null;

                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.list_folder', ['pool' => ':pool'])}}');
                    resource.get({pool: this.currentPool.uid}, {parent_uid: this.currentFolder, with_trash:this.showDeleted}, function (data, status, request) {
                        this.list_folder = data.data;
                        this.folder_meta = data.meta;

                        window.location.hash = this.currentPool.uid + ':' + (this.folder_meta.parent_uid ? this.folder_meta.parent_uid : '');

                        this.$nextTick(function () {
                            initializeComponents()
                        })

                    }.bind(this)).error(function (data, status, request) {
                        toastr.error(data.message, 'Error: ' + status);
                        this.currentFolder = null
                    });

                    fineUploaderBasicInstanceImages.setEndpoint(
                            Vue.url('{{ apiRoute('v1', 'api.documents.file.store', ['pool' => ':pool'])}}', {pool: this.currentPool.uid})
                    );
                    fineUploaderBasicInstanceImages.setParams({
                        parent_uid: this.currentFolder
                    });
                },
                objectEdit: function (object, event) {
                    event.preventDefault();

                    this.editObject = object;
                    this.editMode = 'rename';

                    setTimeout(function () {
                                $('#objectEditInput-'+object.uid).focus()
                            }
                            , 50);
                },
                objectBlurEdit: function (object, event) {
                    event.preventDefault();

                    if (this.editObject == null || this.editMode != 'rename') {
                        return;
                    }

                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.file.update', ['pool' => ':pool'])}}');
                    resource.update({pool: this.currentPool.uid}, this.editObject, function (data, status, request) {
                        this.editMode = null;
                        this.editObject = null;
                    }.bind(this)).error(function (data, status, request) {
                        toastr.error(data.errors[0], 'Error: ' + data.message);
                    });

                },
                objectDelete: function (object, event) {
                    event.preventDefault();

                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.file.destroy', ['pool' => ':pool'])}}');
                    resource.delete({pool: this.currentPool.uid}, object, function (data, status, request) {
                        if(status)
                        {
                            if(this.showDeleted) {
                                object.deleted = true;
                            } else {
                                this.list_folder.$remove(object);
                            }


                            if (object.tag == 'file') {
                                this.folder_meta.objects.files--;
                                this.currentPool.objects.files--;
                            }
                        }
                        console.log(data, status, request);
                    }.bind(this)).error(function (data, status, request) {
                        toastr.error(data.errors[0], 'Error: ' + data.message);
                    });


                },
                objectKeydownEdit: function (object, event) {
                    if (event.which == 13) {
                        this.objectBlurEdit(object, event);
                    }
                },
                createFolder: function (object, event) {
                    event.preventDefault();
                    this.editObject = {
                        mimeType: "application/x-directory",
                        parent_uid: this.currentFolder,
                        pool_uid: this.currentPool.uid,
                        title: ""
                    };
                    this.editMode = 'createFolder';

                    setTimeout(function () {
                                $('#createFolderInput').focus()
                            }
                            , 50);
                },
                folderBlurCreate: function (object, event) {
                    event.preventDefault();

                    if (this.editObject == null || this.editMode != 'createFolder') {
                        return;
                    }

                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.create_folder', ['pool' => ':pool'])}}');
                    resource.save({pool: this.currentPool.uid}, this.editObject, function (data, status, request) {
                        this.editMode = null;
                        this.editObject = null;
                        this.list_folder.push(data.data);
                        this.folder_meta.objects.folders++;
                        this.currentPool.objects.folders++;
                        initializeComponents();
                    }.bind(this)).error(function (data, status, request) {
                        toastr.error(data.errors[0], data.message);
                        this.editMode = null;
                        this.editObject = null;
                    }.bind(this));

                },
                folderKeydownCreate: function (object, event) {
                    if (event.which == 13) {
                        this.folderBlurCreate(object, event);
                    }
                },
                fileUploadStart: function () {
                    this.editMode = 'uploadFiles';
                },
                fileUploadComplete: function (responseJSON) {
                    if (responseJSON.data.uid) {
                        this.list_folder.push(responseJSON.data);
                        this.folder_meta.objects.files++;
                        this.currentPool.objects.files++;
                        initializeComponents();
                    }
                },
                fileUploadAllComplete: function (responseJSON) {
                    this.editMode = null;
                }

            }

        });


        var dragAndDropModule = new fineUploader.DragAndDrop({
            dropZoneElements: [document.getElementById('file-list-table')],
            classes: {
                dropActive: 'blue'
            },
            callbacks: {
                processingDroppedFilesComplete: function (files, dropTarget) {
                    fineUploaderBasicInstanceImages.addFiles(files);
                }
            }
        });

        var fineUploaderBasicInstanceImages = new fineUploader.FineUploaderBasic({
            button: document.getElementById('uploadFileButton'),
            request: {
                endpoint: '',
                inputName: 'data-binary',
                customHeaders: {
                    "Authorization": "Bearer {{$jwtoken}}"
                }
            },
            callbacks: {
                onComplete: function (id, name, responseJSON) {
                    VueInstance.fileUploadComplete(responseJSON)
                },
                onUpload: function () {
                    VueInstance.fileUploadStart();
                },
                onTotalProgress: function (totalUploadedBytes, totalBytes) {
                    $('#uploadFileProgrssbar').progress({
                        percent: Math.ceil(totalUploadedBytes / totalBytes * 100)
                    });
                },
                onAllComplete: function (succeeded, failed) {
                    VueInstance.fileUploadAllComplete(succeeded, failed);
                }
            }
        });

    </script>
@endsection
