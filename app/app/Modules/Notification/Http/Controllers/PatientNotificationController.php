<?php

namespace App\Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Notification\Models\PatientNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $query = PatientNotification::where('user_id', $request->user()->id);

        if ($filter === 'clinic') {
            $query->where(fn ($q) => $q->where('type', 'like', 'appointment.%')->orWhere('type', 'like', 'enrollment.%'));
        } elseif ($filter === 'system') {
            $query->where('type', 'not like', 'appointment.%')->where('type', 'not like', 'enrollment.%');
        }

        $notifications = $query->orderByDesc('created_at')->limit(50)->get();

        PatientNotification::where('user_id', $request->user()->id)->whereNull('read_at')->update(['read_at' => now()]);

        return view('patient.notifications.index', ['notifications' => $notifications, 'filter' => $filter]);
    }
}
