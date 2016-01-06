<?php

$api->version('v1', function ($api) {
    $api->group([
        'prefix'     => 'documents',
        'namespace'  => $this->namespace.'\api',
        'middleware' => config('society.core.core.middleware.api.backend', []),
        'providers'  => ['jwt'],
    ], function ($api) {

        $api->resource('file', 'FileController');

        $api->resource('download', 'FileController', ['only' => 'show']);

    });
});
