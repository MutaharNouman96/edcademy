<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <main class="p-6">
        {{ $slot }}
    </main>
</x-app-layout>
