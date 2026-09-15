<?php

namespace App\Modules\AdminAnalytics\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalPatients' => User::role('patient')->count(),
            'totalClinics' => Clinic::count(),
            'clinicsPendingVerification' => Clinic::whereIn('verification_status', ['submitted', 'under_review'])->count(),
            'totalSuppliers' => Supplier::count(),
            'suppliersPendingVerification' => Supplier::whereIn('verification_status', ['submitted', 'under_review'])->count(),
            'productsPendingModeration' => Product::where('moderation_status', 'pending')->count(),
            'signupTrend' => $this->signupTrend(),
            'appointmentStatusBreakdown' => $this->appointmentStatusBreakdown(),
        ]);
    }

    /** New user registrations per day, last 14 days — platform growth line. */
    private function signupTrend(): array
    {
        $counts = User::where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('j M');
            $data[] = (int) ($counts[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function appointmentStatusBreakdown(): array
    {
        $counts = Appointment::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $statuses = ['requested', 'confirmed', 'completed', 'cancelled', 'declined', 'no_show'];

        return [
            'labels' => array_map(fn ($s) => ucfirst(str_replace('_', ' ', $s)), $statuses),
            'data' => array_map(fn ($s) => (int) ($counts[$s] ?? 0), $statuses),
        ];
    }
}
