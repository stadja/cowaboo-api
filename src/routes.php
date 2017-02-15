<?php

Route::get('/api',
	array('as' => 'api.show', 'uses' => function () {
		return view('cowaboo_api::swagger/index');
	})
);

Route::get('/api/swagger.json',
	array('as' => 'api.json', 'uses' => function () {
		$contents = View::make('cowaboo_api::swagger/api-json');
		$response = Response::make($contents);
		$response->header('Content-Type', 'application/json');
		return $response;
	})
);

Route::get('/api/taglist',
	array('as' => 'api.tagList', 'uses' => function () {
		$tagList = TagList::getCurrent();
		$contents = IPFS::getRaw($tagList->hash);
		$response = Response::make($contents);
		$response->header('Content-Type', 'application/json');
		return $response;
	})
);

Route::post('/api/observatory/conf',
	array('as' => 'api.observatory.metadata.add', 'uses' => 'Cowaboo\Api\ApiController@addConfigurationToObservatory')
);

Route::post('/api/entry/conf',
	array('as' => 'api.observatory.metadata.add', 'uses' => 'Cowaboo\Api\ApiController@addConfigurationToEntry')
);

Route::delete('/api/observatory',
	array('as' => 'api.observatory.delete', 'uses' => 'Cowaboo\Api\ApiController@deleteObservatory')
);

Route::post('/api/observatory',
	array('as' => 'api.observatory.create', 'uses' => 'Cowaboo\Api\ApiController@createObservatory')
);

Route::get('/api/observatory',
	array('as' => 'api.observatory.show', 'uses' => 'Cowaboo\Api\ApiController@getObservatory')
);

Route::get('/api/user/observatories/unsubscribed',
	array('as' => 'api.user.observatories.unsubscribed', 'uses' => 'Cowaboo\Api\ApiController@getUserUnsubscribedObservatories')
);

Route::get('/api/user',
	array('as' => 'api.user.info', 'uses' => 'Cowaboo\Api\ApiController@getUser')
);

Route::post('/api/user',
	array('as' => 'api.user.create', 'uses' => 'Cowaboo\Api\ApiController@createUser')
);

Route::get('/api/user/observatories',
	array('as' => 'api.user.observatories.show', 'uses' => 'Cowaboo\Api\ApiController@getUserObservatories')
);

Route::post('/api/user/observatories',
	array('as' => 'api.user.observatories.suscribe', 'uses' => 'Cowaboo\Api\ApiController@suscribe')
);

Route::delete('/api/user/observatories',
	array('as' => 'api.user.observatories.unsuscribe', 'uses' => 'Cowaboo\Api\ApiController@unsuscribe')
);

Route::put('/api/entry',
	array('as' => 'api.observatory.entry.modify', 'uses' => 'Cowaboo\Api\ApiController@modifyEntry')
);

Route::delete('/api/entry',
	array('as' => 'api.observatory.entry.delete', 'uses' => 'Cowaboo\Api\ApiController@deleteEntry')
);

Route::post('/api/entry',
	array('as' => 'api.observatory.entry.create', 'uses' => 'Cowaboo\Api\ApiController@createEntry')
);
