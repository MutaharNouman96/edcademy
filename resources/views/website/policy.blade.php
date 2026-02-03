<x-guest-layout>
    <div class="container py-5">
        <div class="">

            <h1>{{ $policy->name }}</h1>
            <hr>

            {!! $policy->content !!}
        </div>
    </div>
</x-guest-layout>
