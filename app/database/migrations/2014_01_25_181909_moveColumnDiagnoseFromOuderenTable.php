<?php

use Illuminate\Database\Migrations\Migration;

class MoveColumnDiagnoseFromOuderenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ouderen', function($table)
        {
            $table->dropColumn('diagnose');
        });
        Schema::table('ouderen', function($table)
        {
            $table->text('diagnose')->nullable()->after('birthday');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('ouderen', function($table)
        {
            $table->dropColumn('diagnose');
        });
        Schema::table('ouderen', function($table)
        {
            $table->text('diagnose')->nullable();
        });

	}

}