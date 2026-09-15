<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Platform Admin — Dental Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-dc-text px-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-xl">
        <h1 class="text-xl font-bold text-dc-text">Platform administration</h1>
        <p class="mt-1 text-sm text-dc-text-secondary">Authorized staff only.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                <input class="dc-input" type="email" id="email" name="email" required autofocus>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="password">Password</label>
                <input class="dc-input" type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="dc-btn-primary w-full">Sign in</button>
        </form>
    </div>
</body>
</html>
