@extends('layouts.master')

@section('title')
    {{ trans('documents::documents.title.documents') }}
@endsection
@section('subTitle')
    ExamplePage
@endsection

@section('content')

    <div class="ui breadcrumb filepath">
        <a class="section">
            <i class="home icon"></i>
            SocietyCMS
        </a>
        <i class="right angle icon divider"></i>
        <a class="section">modules</a>
        <i class="right angle icon divider"></i>

        <div class="active section">Documents</div>
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
        <div class="four wide column">

            <div class="ui fluid vertical pointing menu filepools">
                <a class="item" v-for="pool in pools" v-on:click="currentPool = pool.uid"
                   v-bind:class="{ 'active': currentPool == pool.uid }">
                    <i class="large home middle aligned icon"></i>
                    <div class="ui label"> @{{ pool.files.count }}</div>
                    @{{ pool.title }}
                </a>
            </div>

        </div>
        <div class="twelve wide column">

            <table class="ui sortable selectable table">
                <thead>
                <tr>
                    <th class="therteen wide filename">
                        Name
                    </th>
                    <th class="one wide no-sort" 6>

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
                <tr v-for="file in list_folder">
                    <td class="selectable" data-sort-value="@{{ file.title }}" data-tag="@{{ file.tag }}">
                        <a href="#"><i v-bind:class="file.mimeType | semanticFileTypeClass"
                                       class="icon"></i> @{{ file.title }}<span class="ui gray text"
                                                                                v-if="file.fileExtension">.@{{ file.fileExtension }}</span></a>
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
                                <div class="item">
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
                    <td class="right aligned collapsing" data-sort-value="@{{ file.fileSize }}"
                        v-text="file.fileSize | humanReadableFilesize"></td>
                    <td class="right aligned collapsing"
                        data-sort-value="@{{ file.created_at.timestamp }}">@{{ file.created_at.diffForHumans }}</td>
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
    <script>
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


        Vue.filter('humanReadableFilesize', function (size) {
            if (size) {
                return filesize(size, {round: 0});
            }
            return '';
        });

        Vue.filter('semanticFileTypeClass', function (mime) {
            if (semanticFileTypeClassMap[mime]) {
                return semanticFileTypeClassMap[mime]
            }
            return "file outline"
        });

        var VueInstance = new Vue({
            el: '#content',
            data: {
                currentPool: null,
                currentFolder: null,
                pools: null,
                pool_meta: null,
                list_folder: null,
                folder_meta: null
            },
            ready: function () {

                this.$http.get('{{apiRoute('v1', 'api.documents.pool.index')}}', function (data, status, request) {
                    this.$set('pools', data.data);
                    this.$set('pool_meta', data.meta);

                    this.$set('currentPool', data.data[0].uid);
                }.bind(this)).error(function (data, status, request) {
                })

            },
            watch: {
                'currentPool': function (val, oldVal) {
                    var resource = this.$resource('{{apiRoute('v1', 'api.documents.list_folder', ['pool' => ':pool'])}}');
                    resource.save({pool: this.currentPool}, {parent_uid: this.currentFolder}, function (data, status, request) {
                        this.list_folder = data.data;
                        this.folder_meta = data.meta;
                    }.bind(this)).error(function (data, status, request) {
                    });
                }
            },
            methods: {}

        });
    </script>
@endsection
