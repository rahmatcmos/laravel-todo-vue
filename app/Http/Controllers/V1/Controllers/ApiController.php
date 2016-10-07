<?php namespace App\Http\Controllers\V1\Controllers;

use App\Exceptions\InvalidMethodParameterException;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	public function respond ($data, $statusCode = 200)
	{
		return response()->json($data, $statusCode);
	}

	public function dataCreated ($message, $customStatusCode, $data, $statusCode = 201)
	{
		if ( is_numeric($data) ) {
			$statusCode = $data;
			$data = [ ];
		}

		return $this->respond($this->successResponseBuilder($message, $customStatusCode, $data), $statusCode);
	}

	private function successResponseBuilder ($message, $customStatusCode = 2001, $data)
	{
		return array_filter(array_merge([
			'error'   => false,
			'message' => $message,
			'status'  => $customStatusCode,
		], [ 'data' => is_array($data) ? $data : [ $data ] ]), function ($item) {
			if ( is_array($item) ) {
				return !empty( $item );
			}

			return true;
		});
	}

	public function genericError ($message, $customStatusCode, $errorBag, $statusCode = 400)
	{
		if ( is_numeric($errorBag) ) {
			$statusCode = $errorBag;
			$errorBag = [ ];
		}

		return $this->respond($this->errorResponseBuilder($message, $customStatusCode, $errorBag), $statusCode);
	}

	public function validationError ($message, $customStatus, $errorBag, $statusCode = 422)
	{
		if ( is_numeric($errorBag) ) {
			$statusCode = $errorBag;
			$errorBag = [ ];
		} elseif ( !is_array($errorBag) ) {
			throw new InvalidMethodParameterException("Errors must be an array.");
		}

		return $this->respond($this->errorResponseBuilder($message, $customStatus, $errorBag), $statusCode);
	}

	private function errorResponseBuilder ($message, $customStatusCode = 4001, array $errorBag)
	{
		return array_filter(array_merge([
			'error'   => true,
			'message' => $message,
			'status'  => $customStatusCode,
		], [ 'reasons' => $errorBag ]));

	}
}