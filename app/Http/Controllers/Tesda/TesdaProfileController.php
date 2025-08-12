<?php

namespace App\Http\Controllers\Tesda;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Gender;
use App\Models\Skill;
use Illuminate\Support\Facades\Storage;

class TesdaProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['address', 'skills']);

        return view('tesda.profile-view', [
            'user' => $user
        ]);
    }
}
