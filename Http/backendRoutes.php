<?php

$router->group(['prefix' => '/documents'], function () {
	get('/', ['as' => 'backend::documents.documents.index', 'uses' => 'DocumentsController@index']);
});