<?php

$router->group(['prefix' => '/documents'], function () {
	get('/', ['middleware' => ['permission:documents::access-documents'], 'as' => 'backend::documents.documents.index', 'uses' => 'DocumentsController@index']);
});