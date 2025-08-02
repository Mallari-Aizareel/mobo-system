<?php

namespace App\Http\Controllers;

use App\Models\AgencyRepresentative;
use App\Models\Address;
use Illuminate\Http\Request;

class AgencyRepresentativeController extends Controller
{
    public function create()
    {
        return view('portal.agency_account_registration');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_id'     => 'required|exists:users,id',
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'email'         => 'required|email|unique:agency_representatives,email',
            'address_id'    => 'nullable|exists:addresses,id',
        ]);

        AgencyRepresentative::create($validated);

        return redirect()->route('portal.login')->with('success', 'Agency representative registered successfully.');
    }
}
