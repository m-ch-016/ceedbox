<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the route is not in the except array, perform CSRF verification
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        $this->ensureRefererIsValid($request);

        $this->tokensMatch($request);

        return $next($request);
    }

    /**
     * Determine if the request is being excluded from CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray(Request $request)
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ensure that the referer is valid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    protected function ensureRefererIsValid(Request $request)
    {
        if (! $this->tokensMatch($request)) {
            throw new \Illuminate\Session\TokenMismatchException;
        }
    }

    /**
     * Verify that the session token matches the request token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch(Request $request)
    {
        return $request->session()->token() === $request->input('_token');
    }
}
