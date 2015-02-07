<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWoonsituatieToOudere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ouderen', function(Blueprint $table){
			$table->integer('woonsituatie')->unsigned()->nullable();
			$table->foreign('woonsituatie', 'woonsituatie_ouderen')->references('id')->on('meta_values');
		});

		$context = Meta\Context::create([
			'context' => 'ouderen_woonsituatie'
		]);

		$values = [
			['value' => 'Woont samen met mantelzorger'],
			['value' => 'Woont elders, alleen'],
			['value' => 'Woont elders, samen met andere persoon/personen']
		];

		foreach($values as $value)
		{
			$value = new Meta\Value($value);
			$context->values()->save($value);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ouderen', function(Blueprint $table){
			$table->dropForeign('woonsituatie_ouderen');
			$table->dropColumn('woonsituatie');
		});

		$context = Meta\Context::where('context', Meta\Context::OUDEREN_WOONSITUATIE)->first();

		Meta\Value::where('context_id', $context->id)->delete();
		$context->delete();
	}

}
