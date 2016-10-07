<?php namespace App\Http\Controllers\V1\Transformers;

use Illuminate\Contracts\Pagination\Paginator;

abstract class BaseTransformer
{
	abstract public function transform ($item);

	public function transformCollection ($data, $callback, $pagination_key_name = "pagination")
	{
		if ( !$data instanceof Paginator ) {
			return [ ];
		}

		return [
			$pagination_key_name => array_map(function ($item) use ($callback) {
				return $callback($item);
			}, $data),
		];
	}
}