<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsDiagnose extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ouderen', function(Blueprint $table)
		{
			$table->text('details_diagnose');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ouderen', function(Blueprint $table)
		{
			$table->dropColumn('details_diagnose');
		});
	}

}
