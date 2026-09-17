@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="Staff" :nav="$nav">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-dc-success/30 bg-dc-success-bg p-4 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="dc-card overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dc-border">
                        @forelse ($staff as $member)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $member->user->name }}</p>
                                    <p class="text-xs text-dc-text-secondary">{{ $member->user->email }}</p>
                                </td>
                                <td class="px-4 py-3">{{ $member->title ?? '-' }}</td>
                                <td class="px-4 py-3"><span class="dc-badge bg-dc-mint text-dc-teal-deep">{{ $member->user->getRoleNames()->first() }}</span></td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('clinic.staff.destroy', $member) }}" onsubmit="return confirm('Remove this staff member?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-dc-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-dc-text-secondary">No staff added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <div class="dc-card p-5">
                <h2 class="font-semibold">Add staff member</h2>
                <p class="mt-1 text-xs text-dc-text-secondary">If they don't have a Dental Connect account yet, one is created and a temporary password is shown once. Share it with them directly.</p>
                <form method="POST" action="{{ route('clinic.staff.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <input class="dc-input" type="text" name="name" placeholder="Full name" value="{{ old('name') }}" required>
                    <input class="dc-input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    <input class="dc-input" type="tel" name="phone" placeholder="Phone (optional)" value="{{ old('phone') }}">
                    <input class="dc-input" type="text" name="title" placeholder="Title (e.g. Receptionist)" value="{{ old('title') }}">
                    <select class="dc-input" name="role" required>
                        <option value="clinic_staff" @selected(old('role') === 'clinic_staff')>Staff (limited access)</option>
                        <option value="clinic_admin" @selected(old('role') === 'clinic_admin')>Admin (broad access)</option>
                    </select>
                    <button type="submit" class="dc-btn-primary w-full">Add staff member</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
