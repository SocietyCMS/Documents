@extends('layouts.master')

@section('title')
    {{ trans('documents::documents.title.documents') }}
@endsection
@section('subTitle')
    ExamplePage
@endsection

@section('content')

    <div class="ui breadcrumb filepath" v-if="currentPool">
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

        <div class="ui icon top left pointing dropdown button">
            <i class="plus icon"></i>

            <div class="menu">
                <div class="item">Upload</div>
                <div class="item">Folder</div>
            </div>
        </div>

    </div>

    <div class="ui divider"></div>


    <div class="ui grid">
        <div class="four wide column" v-if="pools.length > 1">

            <div class="ui fluid vertical pointing menu filepools">

                <a class="item" v-for="pool in pools" v-on:click="poolClick(pool);"
                   v-bind:class="{ 'active': currentPool.uid == pool.uid }">
                    <i class="large home middle aligned icon"></i>

                    <div class="ui label"
                         v-bind:class="{ 'blue': currentPool.uid == pool.uid }"> @{{ pool.files.count }}</div>
                    @{{ pool.title }}
                </a>
            </div>

        </div>
        <div v-bind:class="{ 'twelve': pools.length > 1, 'wide': pools.length > 1, 'column': pools.length > 1, 'column': pools.length = 1}">

            <table class="ui sortable selectable table">
                <thead>
                <tr>
                    <th class="therteen wide filename">
                        Name
                    </th>
                    <th class="one wide no-sort">
                    </th>
                    <th class="one wide right aligned">
                        Size
                    </th>
                    <th class="two wide right aligned">
                        Modified
                    </th>
                </tr>
                </thead>
                <tbody>

                <tr class="object" v-for="object in list_folder">
                    <td class="selectable" data-sort-value="@{{ object.title }}" data-tag="@{{ object.tag }}">
                        <a href="#" class="title" v-bind:class="{'edit': editObject == object}"
                           v-on:click="objectClick(object, $event)"><i
                                    v-bind:class="object.mimeType | semanticFileTypeClass"
                                    class="icon"></i>

                            <div class="ui text">@{{ object.title }} <span class="ui gray text"
                                                                           v-if="object.fileExtension">.@{{ object.fileExtension }}</span>
                            </div>
                            <div class="ui fluid input">
                                <input type="text" v-model="object.title" v-on:blur="objectBlurEdit(object, $event)"
                                       v-on:keydown="objectKeydownEdit(object, $event)">
                            </div>

                        </a>
                    </td>
                    <td class="collapsing">
                        <button class="circular ui icon button"><i class="share alternate icon"></i></button>

                        <div class="ui top left pointing dropdown">
                            <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>

                            <div class="menu">
                                <div class="item">
                                    <span class="description">ctrl + o</span>
                                    Open...
                                </div>
                                <div class="item">
                                    <span class="description">ctrl + s</span>
                                    Save as...
                                </div>
                                <div class="item" v-on:click="objectEdit(object, $event)">
                                    <span class="description">ctrl + r</span>
                                    Rename
                                </div>
                                <div class="item">Make a copy</div>
                                <div class="item">
                                    <i class="folder icon"></i>
                                    Move to folder
                                </div>
                                <div class="item">
                                    <i class="trash icon"></i>
                                    Move to trash
                                </div>
                                <div class="divider"></div>
                                <div class="item">Download As...</div>
                                <div class="item">
                                    <i class="dropdown icon"></i>
                                    Publish To Web
                                    <div class="menu">
                                        <div class="item">Google Docs</div>
                                        <div class="item">Google Drive</div>
                                        <div class="item">Dropbox</div>
                                        <div class="item">Adobe Creative Cloud</div>
                                        <div class="item">Private FTP</div>
                                        <div class="item">Another Service...</div>
                                    </div>
                                </div>
                                <div class="item">E-mail Collaborators</div>
                            </div>
                        </div>

                    </td>
                    <td class="right aligned collapsing" data-sort-value="@{{ object.objectSize }}"
                        v-text="object.objectSize | humanReadableFilesize"></td>
                    <td class="right aligned collapsing"
                        data-sort-value="@{{ object.created_at.timestamp }}">@{{ object.created_at.diffForHumans }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th><span v-if="folder_meta">@{{ folder_meta.objects.folders }}
                            folders and @{{ folder_meta.objects.files }} files</span></th>
                    <th></th>
                    <th class="right aligned collapsing">83 MB</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

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

            $('.ui.dropdown')
                    .dropdown();
            $('.ui.sortable.table').tablesort();


            $('.ui.sortable.table th.filename').data('sortBy', function (th, td, tablesort) {
                var tag = $(td).data('tag');

                if (tag == 'folder') {
                    return '0' + $(td).data('sort-value').toLowerCase();
                }
                return '1' + $(td).data('sort-value').toLowerCase();
            });

            $('.ui.sortable.table').tablesort().data('tablesort').sort($(".ui.sortable.table th.filename"));
        }

        var VueInstance = new Vue({
            el: '#content',
            data: {
                currentPool: null,
                currentFolder: null,
                pools: {},
                pool_meta: null,
                list_folder: null,
                folder_meta: null,
                editObject: null,
                editMode: null
            },
            ready: function () {

                this.$http.get('{{apiRoute('v1', 'api.documents.pool.index')}}', function (data, status, request) {
                    this.$set('pools', data.data);
                    this.$set('pool_meta', data.meta);

                    this.$set('currentPool', data.data[0]);
                    this.setFromURL();
                }.bind(this)).error(function (data, status, request) {
                })


            },
            watch: {
                'currentFolder': function (val, oldVal) {
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
                poolClick: function (pool) {
                    if (this.currentPool != pool) {
                        this.currentPool = pool;
                    }
                    if (this.currentFolder == null) {
                        return this.listFolder()
                    }
                    return this.currentFolder = null
                },
                objectClick: function (object, event) {
                    event.preventDefault()

                    if (this.editObject == object) {
                        return;
                    }
                    if (object.tag == 'folder') {
                        return this.currentFolder = object.uid
                    }

                    return console.log(object.downloadUrl);
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
                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.list_folder', ['pool' => ':pool'])}}');
                    resource.get({pool: this.currentPool.uid}, {parent_uid: this.currentFolder}, function (data, status, request) {
                        this.list_folder = data.data;
                        this.folder_meta = data.meta;

                        window.location.hash = this.currentPool.uid + ':' + this.folder_meta.parent_uid;

                        this.$nextTick(function () {
                            initializeComponents()
                        })

                    }.bind(this)).error(function (data, status, request) {
                    });
                },
                objectEdit: function (object, event) {
                    event.preventDefault();

                    this.editObject = object;
                    this.editMode = 'rename';

                    setTimeout(function () {
                                $('.object .title.edit .input input').focus()
                            }
                            , 50);
                },
                objectBlurEdit: function (object, event) {
                    event.preventDefault();

                    if(this.editObject== null){ return;}

                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.file.update', ['pool' => ':pool'])}}');
                    resource.update({pool: this.currentPool.uid}, this.editObject, function (data, status, request) {

                    }.bind(this)).error(function (data, status, request) {
                    });

                    this.editObject = null;
                    this.editMode = null;
                },
                objectKeydownEdit: function (object, event) {
                    if (event.which == 13) {
                        this.objectBlurEdit(object, event);
                    }
                }


            }

        });


    </script>
@endsection
