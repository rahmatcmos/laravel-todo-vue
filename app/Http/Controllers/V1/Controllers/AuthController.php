<?php namespace App\Http\Controllers\V1\Controllers;

use App\Http\Controllers\V1\Transformers\UserTransformer;
use App\Http\Requests;
use App\Repositories\User\UserRepository;
use App\User;

class AuthController extends ApiController
{
	private $error_config;
	private $success_config;
	private $transformer;
	private $repository;

	public function __construct (UserRepository $userRepository, UserTransformer $userTransformer)
	{
		$this->error_config = config('error_codes');
		$this->success_config = config('success_codes');
		$this->repository = $userRepository;
		$this->transformer = $userTransformer;
	}

	public function register (Requests\V1\RegisterUserRequest $request)
	{
		$user = $this->repository->insertOrUpdateUser($request);

		if ( !$user ) {
			return $this->genericError($this->error_config['processing_error']['message'], $this->error_config['processing_error']['code'], 500);
		}

		return $this->dataCreated($this->success_config['data_created']['message'], $this->success_config['data_created']['code'], $this->transformer->transform($user));
	}
}
