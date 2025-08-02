<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Gender;
use App\Models\Skill;
use Illuminate\Support\Facades\Storage;

class TesdaAccountSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $skills = Skill::where('user_id', $user->id)->get();
        $genders = Gender::all();

        return view('tesda.account-settings', [
            'user' => $user,
            'skills' => $skills,
            'genders' => $genders,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstname'    => 'required|string|max:255',
            'middlename'   => 'nullable|string|max:255',
            'lastname'     => 'required|string|max:255',
            'gender_id'    => 'required|exists:genders,id',
            'birthdate'    => 'nullable|date',
            'religion'     => 'nullable|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|size:11',
            'street'       => 'nullable|string|max:255',
            'barangay'     => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'province'     => 'nullable|string|max:255',
            'country'      => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'profile_picture'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'background_picture'=> 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'skills.*.name' => 'required|string|max:255',
            'skills.*.percentage' => 'required|integer|min:1|max:100',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        if ($request->hasFile('background_picture')) {
            if ($user->background_picture) {
                Storage::delete('public/' . $user->background_picture);
            }
            $user->background_picture = $request->file('background_picture')->store('background_pictures', 'public');
        }

        if ($request->has('deleted_skills')) {
            foreach ($request->deleted_skills as $skillId) {
                $skill = Skill::where('id', $skillId)->where('user_id', $user->id)->first();
                if ($skill) {
                    $skill->delete();
                }
            }
        }


        if ($request->has('skills')) {
            foreach ($request->skills as $skillData) {
                if (isset($skillData['name'], $skillData['percentage'])) {
                    if (isset($skillData['id'])) {
                        $skill = Skill::where('id', $skillData['id'])->where('user_id', $user->id)->first();
                        if ($skill) {
                            $skill->update([
                                'name' => $skillData['name'],
                                'percentage' => $skillData['percentage'],
                            ]);
                        }
                    } else {
                        Skill::create([
                            'user_id' => $user->id,
                            'name' => $skillData['name'],
                            'percentage' => $skillData['percentage'],
                        ]);
                    }
                }
            }
        }

        $user->update([
            'firstname'    => $request->firstname,
            'middlename'   => $request->middlename,
            'lastname'     => $request->lastname,
            'gender_id'    => $request->gender_id,
            'birthdate'    => $request->birthdate,
            'religion'     => $request->religion,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'description'  => $request->description,
        ]);

        $user->address()->updateOrCreate([], [
            'street'   => $request->street,
            'barangay' => $request->barangay,
            'city'     => $request->city,
            'province' => $request->province,
            'country'  => $request->country,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }


}
