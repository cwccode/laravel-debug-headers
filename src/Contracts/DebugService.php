<?php

namespace CwcCode\LaravelDebugHeaders\Contracts;

interface DebugService
{
    /**
     * Get all the queries.
     *
     * @return array
     */
    public function getQueries();

    /**
     * Get the total query time.
     *
     * @return float|int
     */
    public function getQueryTime();

    /**
     * Get the total app execution time.
     *
     * @return float|int
     */
    public function getAppTime();
}
