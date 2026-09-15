<?php

namespace App\Modules\Marketplace\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * NON-NEGOTIABLE (PRD §11/§45/§46): marketplace access is server-side only.
 * Guests are redirected to login. Patients — and any authenticated user
 * without the `marketplace.access` permission — get a 403. Hiding the nav
 * link is never sufficient; this middleware is the enforcement point.
 */
class EnsureMarketplaceAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        abort_unless($user->isActive(), 403, 'Account is not active.');
        abort_unless($user->can('marketplace.access'), 403, 'Marketplace access is restricted to verified clinics, suppliers and authorized administrators.');

        return $next($request);
    }
}
