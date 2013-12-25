<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
        $this->call('MantelzorgerSeeder');
        $this->call('OudereSeeder');

        $this->call('QuestionnaireSeeder');
        $this->call('PanelSeeder');
        $this->call('QuestionSeeder');
        $this->call('ChoiseSeeder');
    }

}