@extends('layouts.master')

@section('title')
    {{ trans('documents::documents.title.documents') }}
@endsection

@section('styles')
    <link href="{{\Pingpong\Modules\Facades\Module::asset('documents:css/Documents.css')}}" rel="stylesheet"
          type="text/css">
@endsection

@section('content')


    @include('documents::backend.partials.menu')
    <div class="ui bottom attached segment">

        @include('documents::backend.partials.treeview')

    </div>

@endsection
