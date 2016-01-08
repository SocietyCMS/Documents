<?php

$api->version('v1', function ($api) {
    $api->group([
        'prefix'     => 'documents',
        'namespace'  => $this->namespace.'\api',
        'middleware' => config('society.core.core.middleware.api.backend', []),
        'providers'  => ['jwt'],
    ], function ($api) {


        $api->get('pool', ['as' => 'api.documents.pool.index', 'uses'=> 'PoolController@index']);
        $api->post('pool', ['as' => 'api.documents.pool.store', 'uses'=> 'PoolController@store']);
        $api->get('pool/{id}', ['as' => 'api.documents.pool.show', 'uses'=> 'PoolController@get']);
        $api->put('pool/{id}', ['as' => 'api.documents.pool.update', 'uses'=> 'PoolController@update']);
        $api->patch('pool/{id}', ['uses'=> 'PoolController@update']);
        $api->delete('pool/{id}', ['as' => 'api.documents.pool.destroy', 'uses'=> 'PoolController@destroy']);

        $api->get('{pool}/file', ['as' => 'api.documents.file.index', 'uses'=> 'FileController@index']);
        $api->post('{pool}/file', ['as' => 'api.documents.file.store', 'uses'=> 'FileController@store']);
        $api->get('{pool}/file/{id}', ['as' => 'api.documents.file.get', 'uses'=> 'FileController@get']);
        $api->put('{pool}/file/{id}', ['as' => 'api.documents.file.update', 'uses'=> 'FileController@update']);
        $api->patch('{pool}/file/{id}', ['uses'=> 'FileController@update']);
        $api->delete('{pool}/file/{id}', ['as' => 'api.documents.file.destroy', 'uses'=> 'FileController@destroy']);


        $api->get('{pool}/download/{id}', ['as' => 'api.documents.file.download', 'uses'=> 'DownloadController@download']);

        $api->post('{pool}/list_folder',  ['as' => 'api.documents.list_folder', 'uses'=> 'FolderController@list_folder']);

    });
});
