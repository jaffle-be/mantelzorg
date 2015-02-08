<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Meta\Context;
use Meta\Value;

class DiagnoseToMetaFillable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Context::where('context', 'relation_mantelzorger_oudere')
            ->update(['context' => Context::MANTELZORGER_RELATION]);

        $this->addNewColumn();

        $context = $this->createContext();

        $diagnoses = $this->createBaseValues($context);

        $this->batchValues($diagnoses, $context);

        //kolom met oorspronkelijke values hernoemen
        Schema::table('ouderen', function (Blueprint $table) {
            $table->foreign('oorzaak_hulpbehoefte', 'oorzaak_hulpbehoefte_to_meta_values')->references('id')->on('meta_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ouderen', function (Blueprint $table) {
            //make this the actual reverse of the up. we could just delete instead of rename.
            $table->dropForeign('oorzaak_hulpbehoefte_to_meta_values');
            $table->dropColumn('oorzaak_hulpbehoefte');
        });

        $context = Context::where('context', Context::OORZAAK_HULPBEHOEFTE)->first();

        Value::where('context_id', $context->id)->delete();
        $context->delete();

        Context::where('context', Context::MANTELZORGER_RELATION)
            ->update(['context' => 'relation_mantelzorger_oudere']);
    }

    /**
     * @return mixed
     */
    protected function createContext()
    {
        $context = Context::create([
            'context' => Context::OORZAAK_HULPBEHOEFTE,
        ]);

        return $context;
    }

    /**
     * @return array
     */
    protected function getValues()
    {
        return $values = [
            ['value' => 'een lichamelijke handicap'],
            ['value' => 'een verstandelijke handicap'],
            ['value' => 'niet aangeboren hersenletsel'],
            ['value' => 'psychische problemen'],
            ['value' => '(beginnende) dementie/geestelijke achteruitgang'],
            ['value' => 'een acute ziekte of ongeval'],
            ['value' => 'een chronische of terminale ziekte'],
            ['value' => 'algemene beperkingen door ouderdom'],
        ];
    }

    protected function addNewColumn()
    {
        //een extra kolom aanmaken die het diagnose veld voorstelt.
        Schema::table('ouderen', function (Blueprint $table) {
            $table->integer('oorzaak_hulpbehoefte')->unsigned()->nullable();
        });
    }

    /**
     * @param $context
     */
    protected function createBaseValues($context)
    {
        $values = $this->getValues();

        $diagnoses = [];

        foreach ($values as $value) {
            $diagnose = Value::create(array_merge($value, ['context_id' => $context->id]));

            $diagnoses[$diagnose->value] = $diagnose;
        }

        return $diagnoses;
    }

    /**
     * @param $diagnoses
     * @param $context
     */
    protected function batchValues($diagnoses, $context)
    {
//foreach value already defined in diagnose field -> insert it into the values table.
        \Mantelzorger\Oudere::chunk(250, function ($batch) use ($diagnoses, $context) {
            foreach ($batch as $oudere) {
                if (!empty($oudere->diagnose)) {
                    if (!isset($diagnoses[$oudere->diagnose])) {
                        $value = Value::create([
                            'value'      => $oudere->diagnose,
                            'context_id' => $context->id
                        ]);

                        //add to cache
                        $diagnoses[$value->value] = $value;
                    } //this section covers the part when a oudere has an already existing value
                    else {
                        //find the value and set that one instead.
                        $value = $diagnoses[$oudere->diagnose];
                    }

                    $oudere->oorzaak_hulpbehoefte = $value->id;

                    $oudere->save();
                }
            }
        });
    }
}
