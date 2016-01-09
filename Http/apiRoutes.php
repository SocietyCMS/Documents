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
        $api->get('pool/{pool}', ['as' => 'api.documents.pool.show', 'uses'=> 'PoolController@get']);
        $api->put('pool/{pool}', ['as' => 'api.documents.pool.update', 'uses'=> 'PoolController@update']);
        $api->patch('pool/{pool}', ['uses'=> 'PoolController@update']);
        $api->delete('pool/{pool}', ['as' => 'api.documents.pool.destroy', 'uses'=> 'PoolController@destroy']);


        $api->post('{pool}/list_folder',  ['as' => 'api.documents.list_folder', 'uses'=> 'FolderController@list_folder']);
        $api->post('{pool}/create_folder',  ['as' => 'api.documents.create_folder', 'uses'=> 'FolderController@create_folder']);

        //$api->get('{pool}/file_info', ['as' => 'api.documents.file.index', 'uses'=> 'FileController@index']);

        $api->post('{pool}/upload', ['as' => 'api.documents.file.store', 'uses'=> 'FileController@store']);
        $api->post('{pool}/get_metadata', ['as' => 'api.documents.file.get', 'uses'=> 'FileController@get']);
        $api->put('{pool}/update_metadata', ['as' => 'api.documents.file.update', 'uses'=> 'FileController@update']);
        $api->patch('{pool}/update_metadata', ['uses'=> 'FileController@update']);
        $api->delete('{pool}/delete', ['as' => 'api.documents.file.destroy', 'uses'=> 'FileController@destroy']);
        $api->delete('{pool}/permanently_delete', ['as' => 'api.documents.file.forceDestroy', 'uses'=> 'FileController@forceDestroy']);
        $api->post('{pool}/restore', ['as' => 'api.documents.file.restore', 'uses'=> 'FileController@restore']);

        $api->get('{pool}/download/{file}', ['as' => 'api.documents.file.download', 'uses'=> 'DownloadController@download']);


    });
});
