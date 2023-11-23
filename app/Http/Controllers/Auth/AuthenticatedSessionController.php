<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('content.authentications.auth-login-basic');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $intend = null;

        if (Auth::user()->hasAnyPermission(["dashboard-read"])) {
            $intend = RouteServiceProvider::HOME;
        } else if (Auth::user()->hasAnyPermission(["chat-read", "chat-send"])) {
            $intend = route('chats.index');
        } else if (Auth::user()->hasAnyPermission(["contact-read", "contact-create", "contact-edit", "contact-delete"])) {
            $intend = route('contacts.index');
        } else if (Auth::user()->hasAnyPermission(["group-read", "group-create", "group-edit", "group-delete"])) {
            $intend = route('groups.index');
        } else if (Auth::user()->hasAnyPermission(["form-read", "form-create", "form-edit", "form-delete"])) {
            $intend = route('forms.index');
        } else if (Auth::user()->hasAnyPermission(["ticket-read", "ticket-create", "ticket-edit", "ticket-delete"])) {
            $intend = route('tickets.index');
        } else if (Auth::user()->hasAnyPermission(["text-read", "text-create", "text-edit", "text-delete"])) {
            $intend = route('texts.index');
        } else if (Auth::user()->hasAnyPermission(["feedback-read", "feedback-delete"])) {
            $intend = route('feedbacks.index');
        } else if (Auth::user()->hasAnyPermission(["role-read", "role-create", "role-edit", "role-delete"])) {
            $intend = route('roles.index');
        } else if (Auth::user()->hasAnyPermission(["staff-read", "staff-create", "staff-edit", "staff-delete"])) {
            $intend = route('staffs.index');
        } else if (Auth::user()->hasAnyPermission(["report-read"])) {
            $intend = route('reports.index');
        } else if (Auth::user()->hasAnyPermission(["logo-create"])) {
            $intend = route('settings.index');
        }

        return redirect()->intended($intend);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
