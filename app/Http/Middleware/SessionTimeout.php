<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Session\Store;

class SessionTimeout
{
    protected $session;
    protected $timeout;

    public function __construct(Store $session)
    {
        $this->session = $session;
        $this->timeout = config('session.lifetime');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $timezone = config('app.timezone');
        $now = Carbon::createFromTimestamp(strtotime(now()))
            ->timezone($timezone)
            ->timestamp;

        if(!$this->session->has('lastActivityTime'))
        {
            $this->session->put('lastActivityTime',$now);
        } elseif($now - $this->session->get('lastActivityTime') > $this->getTimeOut()){
            $this->session->forget('lastActivityTime');
            $activityTime = $now - $this->session->get('lastActivityTime');
            return redirect()->route('contact.login',[ 'language' => app()->getLocale()])->with('error', 'You had not activity in '.$activityTime/60 .' minutes ago.');
        }
        return $next($request);
    }

    protected function getTimeOut()
    {
        return (env('TIMEOUT')) ?: $this->timeout;
    }
}
