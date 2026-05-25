<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipment    = $query->orderByDesc('created_at')->paginate(15);
        $totalAll     = Equipment::count();
        $available    = Equipment::where('status', 'available')->count();
        $borrowed     = Equipment::where('status', 'borrowed')->count();
        $maintenance  = Equipment::where('status', 'maintenance')->count();

        return view('admin.equipment.index', compact(
            'equipment', 'totalAll', 'available', 'borrowed', 'maintenance'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|in:camera,lens,tripod,lighting,accessory',
            'condition'     => 'required|in:excellent,good,fair,needs_repair',
            'serial_number' => 'nullable|string|max:100|unique:equipment',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }

        Equipment::create($data);
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment added successfully!');
    }

    public function update(Request $request, Equipment $equipment)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:camera,lens,tripod,lighting,accessory',
            'condition'   => 'required|in:excellent,good,fair,needs_repair',
            'status'      => 'required|in:available,borrowed,maintenance',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($equipment->image) Storage::disk('public')->delete($equipment->image);
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment->update($data);
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment updated successfully!');
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->image) Storage::disk('public')->delete($equipment->image);
        $equipment->delete();
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment deleted.');
    }
}