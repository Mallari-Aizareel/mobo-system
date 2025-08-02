<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
 public function index()
{
    $user = Auth::user()->load('address'); // Eager load address
    return view('admin.profile', compact('user'));
}


}
