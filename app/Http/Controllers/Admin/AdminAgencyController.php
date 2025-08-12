<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminAgencyController extends Controller
{
    public function index()
    {
        $agencies = User::with('agencyRepresentatives.address')
    ->where('role_id', 3)
    ->get();


        return view('admin.agencies', compact('agencies'));
    }
}

