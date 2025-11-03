<x-layouts.admin>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}</h3>
        <p>You’re logged in as <strong>Admin</strong>.</p>
    </div>
</x-layouts.admin>
