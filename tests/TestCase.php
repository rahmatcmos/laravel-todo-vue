<?php namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as FrameworkTestcase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends FrameworkTestcase
{
	/**
	 * The base URL to use while testing the application.
	 * @var string
	 */
	protected $baseUrl = 'http://localhost';

	public function urlBuilder ($url, array $replacer)
	{
		foreach ( $replacer as $key => $value ) {
			$url = str_replace($key, $value, $url);
		}

		return $url;
	}

	/**
	 * Creates the application.
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication ()
	{
		return self::initialize();
	}

	private static $configurationApp = null;

	public static function initialize ()
	{
		if ( is_null(self::$configurationApp) ) {
			$app = require __DIR__ . '/../bootstrap/app.php';

			$app->make(Kernel::class)
				->bootstrap();

			Artisan::call('migrate', [ '--database' => 'mysql_testing' ]);
			self::$configurationApp = $app;
		}

		return self::$configurationApp;
	}

	public function tearDown ()
	{
		if ( $this->app ) {
			foreach ( $this->beforeApplicationDestroyedCallbacks as $callback ) {
				call_user_func($callback);
			}

		}

		$this->setUpHasRun = false;

		if ( property_exists($this, 'serverVariables') ) {
			$this->serverVariables = [ ];
		}

		if ( class_exists('Mockery') ) {
			\Mockery::close();
		}

		$this->afterApplicationCreatedCallbacks = [ ];
		$this->beforeApplicationDestroyedCallbacks = [ ];
	}
}
