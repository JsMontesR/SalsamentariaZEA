<?php
namespace App\Http\Middleware;
use Carbon\Carbon;
use Closure;
class PrivateResponse
{
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
        $response->withHeaders([
            'Cache-Control' => 'no-store, no-cache, max-age=0, must-revalidate, private',
            'Expires'       => Carbon::now()->format('D, d M Y H:i:s T'),
        ]);
        return $response;
    }
}