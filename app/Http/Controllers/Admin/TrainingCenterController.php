<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingCenter;

class TrainingCenterController extends Controller
{
    public function index()
    {
        $centers = TrainingCenter::all();
        return view('admin.training-centers', compact('centers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tc_phone_number' => 'required|string|max:20',
            'tc_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'representative' => 'required|string|max:255',
            'r_phone_number' => 'required|string|max:20',
            'r_email' => 'required|email|max:255',
        ]);

        TrainingCenter::create([
            'name' => $request->name,
            'tc_phone_number' => $request->tc_phone_number,
            'tc_email' => $request->tc_email,
            'address' => $request->address,
            'representative' => $request->representative,
            'r_phone_number' => $request->r_phone_number,
            'r_email' => $request->r_email,
        ]);

        return redirect()->back()->with('success', 'Training Center added successfully!');
    }

    public function update(Request $request, $id)
    {
        $center = TrainingCenter::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'tc_phone_number' => 'required|string|max:20',
            'tc_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'representative' => 'required|string|max:255',
            'r_phone_number' => 'required|string|max:20',
            'r_email' => 'required|email|max:255',
        ]);

        $center->update([
            'name' => $request->name,
            'tc_phone_number' => $request->tc_phone_number,
            'tc_email' => $request->tc_email,
            'address' => $request->address,
            'representative' => $request->representative,
            'r_phone_number' => $request->r_phone_number,
            'r_email' => $request->r_email,
        ]);

        return redirect()->back()->with('success', 'Training center updated successfully.');
    }

    public function destroy($id)    
    {
        $center = TrainingCenter::findOrFail($id);
        $center->delete();

        return redirect()->back()->with('success', 'Training center deleted successfully.');
    }


}

