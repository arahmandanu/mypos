<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topping;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToppingController extends Controller
{
    public function index(): View
    {
        $toppings = Topping::orderBy('name')->get();

        return view('admin.toppings.index', compact('toppings'));
    }

    public function create(): View
    {
        return view('admin.toppings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Topping::create($validated);

        return redirect()->route('admin.toppings.index')->with('success', 'Topping created.');
    }

    public function edit(Topping $topping): View
    {
        return view('admin.toppings.edit', compact('topping'));
    }

    public function update(Request $request, Topping $topping): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $topping->update($validated);

        return redirect()->route('admin.toppings.index')->with('success', 'Topping updated.');
    }

    public function destroy(Topping $topping): RedirectResponse
    {
        try {
            $topping->delete();
        } catch (QueryException) {
            return redirect()->route('admin.toppings.index')
                ->with('error', 'Cannot delete this topping — it has existing order history.');
        }

        return redirect()->route('admin.toppings.index')->with('success', 'Topping deleted.');
    }
}
