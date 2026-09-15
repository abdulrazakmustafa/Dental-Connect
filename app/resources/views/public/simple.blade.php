<x-layouts.public :title="$title">
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-dc-text">{{ $heading }}</h1>
        <div class="mt-6 space-y-4 text-dc-text-secondary">
            {!! $body !!}
        </div>
        @isset($cta)
            <a href="{{ $cta['href'] }}" class="dc-btn-primary mt-8">{{ $cta['label'] }}</a>
        @endisset
    </section>
</x-layouts.public>
