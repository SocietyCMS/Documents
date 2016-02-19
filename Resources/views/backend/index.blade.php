@extends('layouts.master')

@section('title')
    {{ trans('documents::documents.title.documents') }}
@endsection

@section('styles')
    <link href="{{\Pingpong\Modules\Facades\Module::asset('documents:css/Documents.css')}}" rel="stylesheet"
          type="text/css">
@endsection

@section('content')

    <div class="ui top attached menu fileMenu" id="documentsApp">
        @include('documents::backend.partials.menu')
    </div>
    <div class="ui bottom attached segment grid fileBrowser">

            <div class="two wide column treeView"> @include('documents::backend.partials.treeview')</div>
            <div class="fourteen wide column fileView" id="fileView">@include('documents::backend.partials.multiview')</div>
    </div>

    @permission('documents::manage-pools')
        @include('documents::backend.partials.createPoolModal')
    @endpermission

@endsection

@section('javascript')
    <script>

        var resourceDocumentsPoolIndex = '{{apiRoute('v1', 'api.documents.pool.index')}}';
        var resourceDocumentsPoolShow = '{{apiRoute('v1', 'api.documents.pool.show', ['pool' => ':uid'])}}';
        var resourceDocumentsPoolStore = '{{apiRoute('v1', 'api.documents.pool.store')}}';


        var resourceDocumentsFileStore = '{{apiRoute('v1', 'api.documents.file.store', ['pool' => ':pool'])}}';

        var resourceDocumentsPoolListFolder = '{{apiRoute('v1', 'api.documents.list_folder', ['pool' => ':uid'])}}';

        var jwtoken = '{{$jwtoken}}';
    </script>
    <script src="{{\Pingpong\Modules\Facades\Module::asset('documents:bundle.js')}}"></script>

@endsection
