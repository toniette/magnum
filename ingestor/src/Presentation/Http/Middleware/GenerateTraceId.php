<?php

declare(strict_types=1);

namespace Src\Presentation\Http\Middleware;

use Closure;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Log\Context\Repository as ContextRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

class GenerateTraceId
{
    public function __construct(
        private ContextRepository $context,
        private ConfigRepository $config
    )
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->context->remember(
            $this->config->string('app.trace_key'),
            Ulid::generate(),
        );
        return $next($request);
    }
}
