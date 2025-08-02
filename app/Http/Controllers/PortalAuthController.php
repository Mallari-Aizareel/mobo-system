<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\Gender;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use App\Models\AgencyRepresentative;
use Illuminate\Support\Facades\DB;

class PortalAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.portal_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('name', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role_id === 2) {
                return redirect()->route('tesda.home');
            } elseif ($user->role_id === 3) {
                return redirect()->route('agency.home');
            } else {
                Auth::logout();
                return redirect()->route('portal.login')->withErrors([
                    'unauthorized' => 'Unauthorized user role.',
                ]);
            }
        }

        return back()->withErrors([
            'login' => 'Invalid username or password.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login')->with('status', 'Logged out successfully.');
    }

    // === REGISTRATION STEP 1 for TESDA users ===
    public function showRegisterForm()
    {
        $roles = Role::whereIn('id', [2, 3])->get();
        return view('auth.portal_register', compact('roles'));
    }

    public function registerStep1(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'username' => 'required|string|max:255|unique:users,name',
            'password' => 'required|string|min:6',
        ]);

        $step1 = [
            'role_id' => $request->input('role_id'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')), 
        ];

        session(['registration.step1' => $step1]);

        if ($step1['role_id'] == 2) {
            return redirect()->route('portal.register.account'); // TESDA
        } elseif ($step1['role_id'] == 3) {
            return redirect()->route('portal.agency.register.account'); // Agency
        }

        return redirect()->route('portal.register')->with('error', 'Invalid role selected.');
    }


    // === REGISTRATION STEP 2 for TESDA users ===
    public function showAccountForm()
    {
        if (!session()->has('registration.step1')) {
            return redirect()->route('portal.register')->with('error', 'Please complete step 1 first.');
        }

        $genders = Gender::all();
        return view('auth.portal.account_registration', compact('genders'));
    }


    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender_id' => 'required|integer',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'street' => 'required|string',
            'barangay' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'country' => 'required|string',
        ]);

        session(['registration.step2' => $validated]);

        return redirect()->route('portal.register.final');
    }

    // === REGISTRATION STEP 3 for TESDA users ===
    public function showFinalStep()
    {
        if (!session()->has('registration.step1') || !session()->has('registration.step2')) {
            return redirect()->route('portal.register')->with('error', 'Please complete previous steps first.');
        }

        return view('auth.portal.final_registration');
    }


    public function registerFinal(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $step1 = session('registration.step1');
        $step2 = session('registration.step2');

        $profilePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user = User::create([
            'role_id' => $step1['role_id'],
            'name' => $step1['username'],
            'password' => $step1['password'],
            'firstname' => $step2['first_name'],
            'lastname' => $step2['last_name'],
            'gender_id' => $step2['gender_id'],
            'birthdate' => $step2['date_of_birth'],
            'phone_number' => $step2['phone'],
            'email' => $step2['email'],
            'profile_picture' => $profilePath,
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'street' => $step2['street'],
            'barangay' => $step2['barangay'],
            'city' => $step2['city'],
            'province' => $step2['province'],
            'country' => $step2['country'],
        ]);

        $user->address_id = $address->id;
        $user->save();

        session()->forget('registration');

        return redirect()->route('portal.login')->with('success', 'Account created successfully. You can now log in.');
    }

    // === REGISTRATION STEP 2 for Agency users ===
    public function showAgencyAccountForm()
    {
        if (!session()->has('registration.step1')) {
            return redirect()->route('portal.register')->with('error', 'Please complete step 1 first.');
        }

        return view('auth.portal.agency_account_registration');
    }

    public function agencyStoreStep2(Request $request)
    {
        $validated = $request->validate([
            'first_name'         => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'email'              => 'required|email|max:255',

            'rep_first_name'     => 'required|string|max:255',
            'rep_last_name'      => 'required|string|max:255',
            'rep_phone_number'   => 'required|string|max:20',
            'rep_email'          => 'required|email|max:255',

            'rep_street'         => 'required|string|max:255',
            'rep_barangay'       => 'required|string|max:255',
            'rep_city'           => 'required|string|max:255',
            'rep_province'       => 'required|string|max:255',
            'rep_country'        => 'required|string|max:255',

            'street'             => 'required|string|max:255',
            'barangay'           => 'required|string|max:255',
            'city'               => 'required|string|max:255',
            'province'           => 'required|string|max:255',
            'country'            => 'required|string|max:255',
        ]);

        session(['registration.agency_step2' => $validated]);

        return redirect()->route('portal.agency.register.final');
    }

    // === REGISTRATION STEP 3 for Agency users ===
    public function showAgencyFinalStep()
    {
        if (!session()->has('registration.step1') || !session()->has('registration.agency_step2')) {
            return redirect()->route('portal.register')->with('error', 'Please complete previous steps first.');
        }

        return view('auth.portal.agency_final_registration');
    }

    public function registerAgencyFinal(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $step1 = session('registration.step1');
        $step2 = session('registration.agency_step2');

        $profilePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        DB::transaction(function () use ($step1, $step2, $profilePath) {
            $user = User::create([
                'role_id' => $step1['role_id'],
                'name' => $step1['username'],
                'password' => $step1['password'],
                'firstname' => $step2['first_name'],
                'lastname' => $step2['last_name'] ?? null,
                'gender_id' => $step2['gender_id'] ?? null,
                'date_of_birth' => $step2['date_of_birth'] ?? null,
                'phone_number' => $step2['phone'],
                'email' => $step2['email'],
                'profile_picture' => $profilePath,
            ]);

            $agencyAddress = Address::create([
                'user_id' => $user->id,
                'street' => $step2['street'],
                'barangay' => $step2['barangay'],
                'city' => $step2['city'],
                'province' => $step2['province'],
                'country' => $step2['country'],
            ]);

            $user->address_id = $agencyAddress->id;
            $user->save();

            $repAddress = Address::create([
                'street' => $step2['rep_street'],
                'barangay' => $step2['rep_barangay'],
                'city' => $step2['rep_city'],
                'province' => $step2['rep_province'],
                'country' => $step2['rep_country'],
            ]);

            AgencyRepresentative::create([
                'agency_id'   => $user->id,
                'first_name'  => $step2['rep_first_name'],
                'last_name'   => $step2['rep_last_name'],
                'phone_number'=> $step2['rep_phone_number'],
                'email'       => $step2['rep_email'],
                'address_id'  => $repAddress->id,
            ]);
        });

        session()->forget('registration');

        return redirect()->route('portal.login')->with('success', 'Agency account successfully registered.');
    }

}
