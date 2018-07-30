<?php

namespace CwcCode\LaravelDebugHeaders\Http\Middleware;

use Closure;
use CwcCode\LaravelDebugHeaders\Contracts\DebugService;
use Symfony\Component\HttpFoundation\Response;

class DebugApi
{
    /**
     * The debug service.
     *
     * @var \CwcCode\LaravelDebugHeaders\Contracts\DebugService
     */
    protected $debugger;

    /**
     * Create a new middleware instance.
     *
     * @param  \CwcCode\LaravelDebugHeaders\Contracts\DebugService  $debugger
     * @return void
     */
    public function __construct(DebugService $debugger)
    {
        $this->debugger = $debugger;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $headers = $response->headers;

            $headers->add([
                'Laravel-Time' => number_format($this->debugger->getAppTime(), 4) . ' s',
                'Laravel-Queries-Time' => number_format($this->debugger->getQueryTime(), 2) . ' ms',
                'Laravel-Queries-Total' => \count($this->debugger->getQueries()),
                'Laravel-Queries' => $this->debugger->getQueries(),
            ]);

            $response->headers = $headers;
        }

        return $response;
    }
}
