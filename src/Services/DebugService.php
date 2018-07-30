<?php

namespace CwcCode\LaravelDebugHeaders\Services;

use CwcCode\LaravelDebugHeaders\Contracts\DebugService as DebugServiceContract;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Database\Events\QueryExecuted;

class DebugService implements DebugServiceContract
{
    /**
     * A collection of queries for debugging.
     *
     * @var array
     */
    protected static $queries = [];

    /**
     * The total time spent querying.
     *
     * @var float
     */
    protected static $queryTime = 0;

    /**
     * The events dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $eventsDispatcher;

    /**
     * Create a new service instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $eventsDispatcher
     * @return void
     */
    public function __construct(EventsDispatcher $eventsDispatcher)
    {
        $this->eventsDispatcher = $eventsDispatcher;

        $this->registerListener();
    }

    /**
     * {@inheritdoc}
     */
    public function getQueries(): array
    {
        return static::$queries;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryTime()
    {
        return static::$queryTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppTime()
    {
        if ( ! \defined('LARAVEL_START')) {
            return 0;
        }

        return microtime(true) - LARAVEL_START;
    }

    /**
     * Register the listener to log queries.
     *
     * @return void
     */
    protected function registerListener()
    {
        $this->eventsDispatcher->listen(QueryExecuted::class, function (QueryExecuted $event) {
            $query = $event->sql;

            foreach ($event->bindings as $key => $binding) {
                $regex = is_numeric($key)
                    ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
                    : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

                $binding = \is_int($binding) ? $binding : $event->connection->getPdo()->quote($binding);

                $query = preg_replace($regex, $binding, $query, 1);
            }

            $time = is_numeric($event->time) ? $event->time : 0;

            static::$queries[] = $query . ' [' . number_format($time, 2) . ' ms]';
            static::$queryTime += $time;
        });
    }
}
