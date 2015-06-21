<?php

use Illuminate\Database\Migrations\Migration;

class UserOrganisations extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($t) {
            $t->integer('organisation_id')->after('admin')->unsigned()->nullable();
            $t->foreign('organisation_id')->references('id')->on('organisations');
            $t->integer('organisation_location_id')->after('organisation_id')->unsigned()->nullable();
            $t->foreign('organisation_location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($t) {
            $t->dropForeign('users_organisation_id_foreign');
            $t->dropColumn('organisation_id');
            $t->dropForeign('users_organisation_location_id_foreign	');
            $t->dropColumn('organisation_location_id');
        });
    }

}