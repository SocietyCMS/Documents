<?php

$router->get('documents/{uri}', ['uses' => 'PublicController@uri', 'as' => 'documents']);
