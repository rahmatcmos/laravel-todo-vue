<?php namespace App\Http\Controllers\V1\Transformers;

use Illuminate\Database\Eloquent\Model;

class UserTransformer extends BaseTransformer
{
	public function transform ($item)
	{
		if ( $item instanceof Model ) {
			return [
				'id'    => $item->id,
				'name'  => $item->name,
				'email' => $item->email,
			];
		}

		return [
			'id'    => $item['id'],
			'name'  => $item['name'],
			'email' => $item['email'],
		];
	}
}