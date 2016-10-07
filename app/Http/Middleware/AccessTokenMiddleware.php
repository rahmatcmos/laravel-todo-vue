<?php namespace App\Http\Middleware;

use App\AccessToken;
use App\Http\Controllers\V1\ApiController;
use Closure;

class AccessTokenMiddleware
{
	public function handle ($request, Closure $next)
	{
		$access_token = $request->get('access_token', null);
		if ( null === $access_token ) {
			$message = config('error_codes.empty_access_token_error.message');
			$status = config('error_codes.empty_access_token_error.code');

			return $this->handleUnauthenticatedUser($message, $status);
		}

		$db_access_token = AccessToken::where('access_token', $access_token)
									  ->first();
		if ( !$db_access_token ) {
			$message = config('error_codes.invalid_access_token_error.message');
			$status = config('error_codes.invalid_access_token_error.code');

			return $this->handleUnauthenticatedUser($message, $status);
		}

		return $next($request);
	}

	private function handleUnauthenticatedUser ($message, $status)
	{
		return (new ApiController())->respond([
			'error'   => true,
			'reasons' => [
				'message' => $message,
			],
			'status'  => $status,
		]);
	}
}
