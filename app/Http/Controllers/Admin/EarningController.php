<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Earning;


class EarningController extends Controller
{
    //
    public function edit(Earning $earning)
    {
        $earning->load(['payment']);
        return view('educator.earnings.edit', compact('earning'));
    }

    public function update(Request $request, Earning $earning)
    {
        $request->validate(['description' => 'nullable|string|max:500']);
        $earning->update(['description' => $request->description]);

        return redirect()->route('educator.earnings.index')->with('success', 'Earning updated successfully.');
    }

    public function destroy(Earning $earning)
    {
        $earning->delete();
        return back()->with('success', 'Earning deleted successfully.');
    }
}
