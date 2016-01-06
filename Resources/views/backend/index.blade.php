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



    <table class="ui sortable selectable table">
        <thead>
        <tr>
            <th class="therteen wide" colspan="2">
                Name
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
        <tr>
            <td class="selectable">
                <a href="#"> <i class="folder icon"></i> node_modules</a>
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
            <td class="right aligned collapsing" data-sort-value="70300000">70.3 MB</td>
            <td class="right aligned collapsing">10 hours ago</td>
        </tr>
        <tr>
            <td>
                <i class="folder icon"></i> test
            </td>
            <td class="collapsing">
                <button class="circular ui icon button"><i class="share alternate icon"></i></button>
                <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>
            </td>
            <td class="right aligned collapsing" data-sort-value="10200000">10.2 MB</td>
            <td class="right aligned">10 hours ago</td>
        </tr>
        <tr>
            <td>
                <i class="folder icon"></i> build
            </td>
            <td class="collapsing">
                <button class="circular ui icon button"><i class="share alternate icon"></i></button>
                <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>
            </td>
            <td class="right aligned collapsing" data-sort-value="709000">709 kB</td>
            <td class="right aligned">10 hours ago</td>
        </tr>
        <tr>
            <td>
                <i class="file outline icon"></i> package.json
            </td>
            <td class="collapsing">
                <button class="circular ui icon button"><i class="share alternate icon"></i></button>
                <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>
            </td>
            <td class="right aligned collapsing" data-sort-value="12000">12 kB</td>
            <td class="right aligned">10 hours ago</td>
        </tr>
        <tr>
            <td>
                <i class="file outline icon"></i> Gruntfile.js
            </td>
            <td class="collapsing">
                <button class="circular ui icon button"><i class="share alternate icon"></i></button>
                <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>
            </td>
            <td class="right aligned collapsing" data-sort-value="308000">308 kB</td>
            <td class="right aligned">10 hours ago</td>
        </tr>
        </tbody>
        <tfoot>
        <tr><th>3 folders and 2 files</th>
            <th></th>
            <th class="right aligned collapsing">83 MB</th>
            <th></th>
        </tr></tfoot>
    </table>

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
    </script>
@endsection