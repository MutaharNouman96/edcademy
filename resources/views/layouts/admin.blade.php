<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-100 p-4 border-r">
            <h3 class="font-bold mb-3">Admin Menu</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="#">Manage Users</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</x-app-layout>
