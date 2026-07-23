<?php

namespace App\Http\Middleware;

use App\Services\StatisticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordStatistics
{
    public function __construct(protected StatisticsService $statistics) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldRecord($request)) {
            $this->statistics->recordPageView($request);
        }

        return $next($request);
    }

    protected function shouldRecord(Request $request): bool
    {
        return $request->isMethod('GET') && ! $request->is('admin*', 'livewire*', 'storage*', 'up');
    }
}
