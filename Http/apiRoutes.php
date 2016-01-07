<?php

$api->version('v1', function ($api) {
    $api->group([
        'prefix'     => 'documents',
        'namespace'  => $this->namespace.'\api',
        'middleware' => config('society.core.core.middleware.api.backend', []),
        'providers'  => ['jwt'],
    ], function ($api) {


        $api->get('{pool}/file', ['as' => 'api.documents.file.index', 'uses'=> 'FileController@index']);
        $api->post('{pool}/file', ['as' => 'api.documents.file.store', 'uses'=> 'FileController@store']);
        $api->get('{pool}/file/{id}', ['as' => 'api.documents.file.get', 'uses'=> 'FileController@get']);
        $api->put('{pool}/file/{id}', ['as' => 'api.documents.file.update', 'uses'=> 'FileController@update']);
        $api->delete('{pool}/file/{id}', ['as' => 'api.documents.file.destroy', 'uses'=> 'FileController@destroy']);


        $api->get('{pool}/download/{id}', ['as' => 'api.documents.file.download', 'uses'=> 'DownloadController@download']);

        $api->post('{pool}/list_folder',  ['as' => 'api.documents.file.list_folder', 'uses'=> 'FolderController@list_folder']);


    });
});
