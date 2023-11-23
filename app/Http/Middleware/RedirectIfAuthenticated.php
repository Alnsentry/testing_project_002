<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(Auth::user()->hasAnyPermission(["dashboard-read"])) {
                    return redirect(RouteServiceProvider::HOME);
                } else if(Auth::user()->hasAnyPermission(["chat-read","chat-send"])) {
                    return redirect()->route('chats.index');
                } else if(Auth::user()->hasAnyPermission(["contact-read","contact-create","contact-edit","contact-delete"])) {
                    return redirect()->route('contacts.index');
                } else if(Auth::user()->hasAnyPermission(["group-read","group-create","group-edit","group-delete"])) {
                    return redirect()->route('groups.index');
                } else if(Auth::user()->hasAnyPermission(["form-read","form-create","form-edit","form-delete"])) {
                    return redirect()->route('forms.index');
                } else if(Auth::user()->hasAnyPermission(["ticket-read","ticket-create","ticket-edit","ticket-delete"])) {
                    return redirect()->route('tickets.index');
                } else if(Auth::user()->hasAnyPermission(["text-read","text-create","text-edit","text-delete"])) {
                    return redirect()->route('texts.index');
                } else if(Auth::user()->hasAnyPermission(["feedback-read","feedback-delete"])) {
                    return redirect()->route('feedbacks.index');
                } else if(Auth::user()->hasAnyPermission(["role-read","role-create","role-edit","role-delete"])) {
                    return redirect()->route('roles.index');
                } else if(Auth::user()->hasAnyPermission(["staff-read","staff-create","staff-edit","staff-delete"])) {
                    return redirect()->route('staffs.index');
                } else if(Auth::user()->hasAnyPermission(["report-read"])) {
                    return redirect()->route('reports.index');
                } else if(Auth::user()->hasAnyPermission(["logo-create"])) {
                    return redirect()->route('settings.index');
                }
            }
        }

        return $next($request);
    }
}
