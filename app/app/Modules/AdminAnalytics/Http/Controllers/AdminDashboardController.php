<?php

namespace App\Modules\AdminAnalytics\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        ]);
    }
}
