<?php namespace Integrated;

trait DatabaseTransactions
{
    /**
     * Begin a new database transaction.
     *
     * @setUp
     * @priority 10
     */
    public function beginTransaction()
    {
        $this->app['db']->beginTransaction();
    }

    /**
     * Rollback the transaction.
     *
     * @tearDown
     * @priority 10
     */
    public function rollbackTransaction()
    {
        $this->app['db']->rollback();
    }
}