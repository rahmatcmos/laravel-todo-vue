<?php namespace App\Http\Requests\V1;

use App\Http\Controllers\V1\Controllers\ApiController;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
	public function authorize ()
	{
		return true;
	}

	public function rules ()
	{
		return [
			'name'     => 'required|max:30',
			'email'    => 'required|email|unique:users,email',
			'password' => 'required|confirmed|min:6|max:12',
		];
	}

	public function response (array $errors)
	{
		return (new ApiController())->validationError(config('error_codes.validation_error.message'), config('error_codes.validation_error.code'), $errors);
	}
}
