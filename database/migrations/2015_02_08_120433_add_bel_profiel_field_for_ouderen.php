<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBelProfielFieldForOuderen extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ouderen', function(Blueprint $table)
		{
			$table->integer('bel_profiel')->unsigned()->nullable();
			$table->foreign('bel_profiel', 'bel_profiel_to_meta_value')->references('id')->on('meta_values');
		});

		$context = App\Meta\Context::create([
			'context' => App\Meta\Context::BEL_PROFIEL
		]);

		$values = [
			['value' => 'score niet gekend'],
			['value' => 'score < 35'],
			['value' => 'scope 35 of meer']
		];

		foreach($values as $value)
		{
			App\Meta\Value::create(array_merge($value, ['context_id' => $context->id]));
		}
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
			$table->dropForeign('bel_profiel_to_meta_value');
			$table->dropColumn('bel_profiel');
		});

		$context = App\Meta\Context::where('context', App\Meta\Context::BEL_PROFIEL)->first();

		App\Meta\Value::where('context_id', $context->id)->delete();
		$context->delete();
	}

}
