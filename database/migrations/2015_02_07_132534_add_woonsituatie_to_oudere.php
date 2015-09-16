<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddWoonsituatieToOudere extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ouderen', function (Blueprint $table) {
            $table->integer('woonsituatie')->unsigned()->nullable();
            $table->foreign('woonsituatie', 'woonsituatie_ouderen')->references('id')->on('meta_values');
        });

        $context = App\Meta\Context::create([
            'context' => 'ouderen_woonsituatie'
        ]);

        $values = [
            ['value' => 'Woont samen met mantelzorger'],
            ['value' => 'Woont elders, alleen'],
            ['value' => 'Woont elders, samen met andere persoon/personen']
        ];

        foreach ($values as $value) {
            $value = new App\Meta\Value($value);
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
        Schema::table('ouderen', function (Blueprint $table) {
            $table->dropForeign('woonsituatie_ouderen');
            $table->dropColumn('woonsituatie');
        });

        $context = App\Meta\Context::where('context', App\Meta\Context::OUDEREN_WOONSITUATIE)->first();

        if ($context) {
            App\Meta\Value::where('context_id', $context->id)->delete();
            $context->delete();
        }
    }

}
