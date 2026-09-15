<?php

namespace App\Modules\Identity\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:255']]);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            ['subscribed_at' => now()]
        );

        return back()->with('status', "Thanks — we'll keep {$data['email']} posted.");
    }
}
