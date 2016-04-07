@extends('layouts.master')

@section('title')
    {{ trans('documents::documents.title.documents') }}
@endsection

@section('content')

    <div class="ui menu fileMenu" id="documentsApp">
        @include('documents::backend.partials.menu')
    </div>

    <div id="fileBrowser">
        <div class="container">
            <div class="ui pointing vertical menu treeView"> @include('documents::backend.partials.treeview')</div>
            <div class="ui segment fileView" id="fileView">@include('documents::backend.partials.multiview')</div>
        </div>
    </div>

    @permission('documents::manage-pools')
        @include('documents::backend.partials.createPoolModal')
        @include('documents::backend.partials.updatePoolModal')
    @endpermission

@endsection

@section('javascript')
    <script>

        var resourceDocumentsPoolIndex = '{{apiRoute('v1', 'api.documents.pool.index')}}';
        var resourceDocumentsPoolShow = '{{apiRoute('v1', 'api.documents.pool.show', ['pool' => ':uid'])}}';
        var resourceDocumentsPoolStore = '{{apiRoute('v1', 'api.documents.pool.store')}}';
        var resourceDocumentsPoolUpdate = '{{apiRoute('v1', 'api.documents.pool.update',['pool' => ':uid'])}}';


        var resourceDocumentsFileStore = '{{apiRoute('v1', 'api.documents.file.store', ['pool' => ':pool'])}}';
        var resourceDocumentsFileUpdate = '{{apiRoute('v1', 'api.documents.file.update', ['pool' => ':pool'])}}';
        var resourceDocumentsFileDestroy = '{{apiRoute('v1', 'api.documents.file.destroy', ['pool' => ':pool'])}}';

        var resourceDocumentsFolderStore = '{{apiRoute('v1', 'api.documents.create_folder', ['pool' => ':pool'])}}';

        var resourceDocumentsPoolListFolder = '{{apiRoute('v1', 'api.documents.list_folder', ['pool' => ':uid'])}}';
    </script>
    <script src="{{\Pingpong\Modules\Facades\Module::asset('documents:bundle.js')}}"></script>
@endsection
