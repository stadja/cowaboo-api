<?php

namespace Cowaboo\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		include __DIR__ . '/routes.php';
		$this->loadViewsFrom(__DIR__ . '/views', 'cowaboo_api');
		$this->publishes([
			__DIR__ . '/views' => resource_path('views/vendor/cowaboo_api'),
		], 'cowaboo_api-views');
		$this->publishes([
			__DIR__ . '/assets' => public_path('vendor/cowaboo_api'),
		], 'cowaboo_api-assets');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->make('Cowaboo\Api\ApiController');
	}
}
