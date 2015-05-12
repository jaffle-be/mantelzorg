<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustOudereMetaFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ouderen', function(Blueprint $table)
		{
			$table->dropForeign('woonsituatie_ouderen');
			$table->dropForeign('bel_profiel_to_meta_value');
			$table->dropForeign('ouderen_mantelzorger_relation_foreign');
			$table->dropForeign('oorzaak_hulpbehoefte_to_meta_values');

			$table->renameColumn('woonsituatie', 'woonsituatie_id');
			$table->renameColumn('mantelzorger_relation', 'mantelzorger_relation_id');
			$table->renameColumn('bel_profiel', 'bel_profiel_id');
			$table->renameColumn('oorzaak_hulpbehoefte', 'oorzaak_hulpbehoefte_id');

			$table->foreign('woonsituatie', 'woonsituatie_ouderen')->references('id')->on('meta_values');
			$table->foreign('mantelzorger_relation')->references('id')->on('meta_values');
			$table->foreign('bel_profiel', 'bel_profiel_to_meta_value')->references('id')->on('meta_values');
			$table->foreign('oorzaak_hulpbehoefte', 'oorzaak_hulpbehoefte_to_meta_values')->references('id')->on('meta_values');
		});

		DB::table('meta_contexts')->where('context', 'ouderen_woonsituatie')->update(['context' => 'woonsituatie_id']);
		DB::table('meta_contexts')->where('context', 'mantelzorger_relation')->update(['context' => 'mantelzorger_relation_id']);
		DB::table('meta_contexts')->where('context', 'bel_profiel')->update(['context' => 'bel_profiel_id']);
		DB::table('meta_contexts')->where('context', 'oorzaak_hulpbehoefte')->update(['context' => 'oorzaak_hulpbehoefte_id']);

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
			$table->renameColumn('woonsituatie_id', 'woonsituatie');
			$table->renameColumn('mantelzorger_relation_id', 'mantelzorger_relation');
			$table->renameColumn('bel_profiel_id', 'bel_profiel');
			$table->renameColumn('oorzaak_hulpbehoefte_id', 'oorzaak_hulpbehoefte');
		});

		DB::table('meta_contexts')->where('context', 'woonsituatie_id')->update(['context' => 'ouderen_woonsituatie']);
		DB::table('meta_contexts')->where('context', 'mantelzorger_relation_id')->update(['context' => 'mantelzorger_relation']);
		DB::table('meta_contexts')->where('context', 'bel_profiel_id')->update(['context' => 'bel_profiel']);
		DB::table('meta_contexts')->where('context', 'oorzaak_hulpbehoefte_id')->update(['context' => 'oorzaak_hulpbehoefte']);
	}

}
