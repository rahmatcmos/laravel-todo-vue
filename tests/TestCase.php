<?php namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as FrameworkTestcase;

abstract class TestCase extends FrameworkTestcase
{
	/**
	 * The base URL to use while testing the application.
	 * @var string
	 */
	protected $baseUrl = 'http://localhost';

	/**
	 * Creates the application.
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication ()
	{
		$app = require __DIR__ . '/../bootstrap/app.php';
		$app->make(Kernel::class)
			->bootstrap();

		return $app;
	}

	public function urlBuilder ($url, array $replacer)
	{
		foreach ( $replacer as $key => $value ) {
			$url = str_replace($key, $value, $url);
		}

		return $url;
	}
}
