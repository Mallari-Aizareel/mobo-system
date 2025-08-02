<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function index()
    {
        $agreements = Agreement::latest()->get();
        return view('admin.agreement', compact('agreements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Agreement::create(['name' => $request->name]);

        return redirect()->route('admin.agreements.index')->with('success', 'Agreement added successfully.');
    }
}
