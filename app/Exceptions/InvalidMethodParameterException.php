<?php
/**
 * Created by PhpStorm.
 * User: anik
 * Date: 10/7/16
 * Time: 12:09 PM
 */

namespace App\Exceptions;

class InvalidMethodParameterException extends \Exception
{
	public function __construct ($message = "Invalid method parameter exception!", $code = 5000)
	{
		parent::__construct($message, $code);
	}

	public function __toString ()
	{
		return sprintf("%d: %s", $this->code, $this->message);
	}
}