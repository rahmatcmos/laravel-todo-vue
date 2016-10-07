<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessTokensTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up ()
	{
		Schema::create('access_tokens', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')
				  ->unsigned();
			$table->string('access_token', 40);
			$table->string('refresh_token', 40);
			$table->dateTime('expires_in');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down ()
	{
		Schema::dropIfExists('access_tokens');
	}
}
