<x-layouts.student>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}</h3>
        <p>You’re logged in as <strong>Student</strong>.</p>
    </div>
</x-layouts.student>
