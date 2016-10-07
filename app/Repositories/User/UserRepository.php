<?php namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository
{
	public function insertOrUpdateUser (Request $request, User $user = null)
	{
		if ( !$user ) {
			$user = new User();
		}

		$user->name = trim($request->get('name'));
		$user->email = trim($request->get('email'));
		$user->password = $request->get('password');
		$user->save();

		return $user->fresh();
	}
}