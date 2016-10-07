<?php namespace Tests\Unit;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
	use DatabaseMigrations;

	private $faker = null;
	private $url = '';
	private $email = '';

	public function __construct ()
	{
		$this->faker = Factory::create();
	}

	public function setUp ()
	{
		parent::setUp();
		#echo str_random(4)."\n";
		$this->url = config('test_urls.registration');
	}

	/** @test */
	public function empty_form_data_test ()
	{
		$this->post($this->url)
			 ->seeJson([ 'error' => true ]);
	}

	/** @test */
	public function empty_name_test ()
	{
		$form = $this->generateUserRegistrationForm([ 'name' => true ]);
		$this->post($this->url, $form)
			 ->seeJsonStructure([ 'reasons' ])
			 ->seeJson([ 'error' => true ]);
	}

	/** @test */
	public function invalid_user_email_test ()
	{
		$form = $this->generateUserRegistrationForm([ 'email' => true ]);
		$this->post($this->url, $form)
			 ->seeJsonStructure([ 'reasons' ])
			 ->seeJson([ 'error' => true ]);
	}

	/** @test */
	public function not_given_confirm_password_test ()
	{
		$form = $this->generateUserRegistrationForm([
			'confirm' => false,
		]);
		$this->post($this->url, $form)
			 ->seeJsonStructure([ 'reasons' => [ 'password' ] ])
			 ->seeJson([ 'error' => true ]);
	}

	/** @test */
	public function password_length_smaller_than_six_test ()
	{
		$form = $this->generateUserRegistrationForm([
			'password_length' => 3,
		]);
		$this->post($this->url, $form)
			 ->seeJsonStructure([ 'reasons' => [ 'password' ] ])
			 ->seeJson([ 'error' => true ]);
	}


	/** @test */
	public function user_must_be_created ()
	{
		$form = $this->generateUserRegistrationForm();
		$this->email = $form['email'];
		$this->post($this->url, $form)
			 ->seeJson()
			 ->seeJson([ 'error' => false ])
			 ->seeJsonStructure([
				 'data' => [
					 'id',
					 'name',
				 ],
			 ]);
	}

	/** @test */
	public function same_email_should_not_be_processes ()
	{
		$form = $this->generateUserRegistrationForm([
			'password_length' => 3,
		]);
		$form['email'] = $this->email;

		$this->post($this->url, $form)
			 ->seeJsonStructure([ 'reasons' => [ 'email' ] ])
			 ->seeJson([ 'error' => true ]);
	}


	private function generateUserRegistrationForm ($corrupt = [ ])
	{
		$password = str_random(isset( $corrupt['password_length'] ) ? $corrupt['password_length'] : 6);

		return array_filter([
			'name'                  => !isset( $corrupt['name'] ) ? $this->faker->name : '',
			'email'                 => !isset( $corrupt['email'] ) ? $this->faker->email : $this->faker->firstName,
			'password'              => $password,
			'password_confirmation' => !isset( $corrupt['confirm'] ) ? $password : '',
		]);
	}
}
