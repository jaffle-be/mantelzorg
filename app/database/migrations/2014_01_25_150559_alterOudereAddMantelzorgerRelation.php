<?php

use Illuminate\Database\Migrations\Migration;

class AlterOudereAddMantelzorgerRelation extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ouderen', function ($t) {
            $t->integer('mantelzorger_relation')->unsigned()->nullable()->after('mantelzorger_id');
            $t->foreign('mantelzorger_relation')->references('id')->on('meta_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ouderen', function ($t) {
            $t->dropForeign('ouderen_mantelzorger_relation_foreign');
            $t->dropColumn('mantelzorger_relation');
        });
    }

}