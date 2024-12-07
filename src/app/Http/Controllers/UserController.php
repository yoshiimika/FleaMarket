<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Address;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function showProfile(Request $request)
    {
        $user = auth()->user();
        $page = $request->query('page', 'sell');
        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
        $items = Item::whereHas('purchases', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        } else {
            $items = collect();
        }
        return view('mypage.mypage', compact('user', 'page', 'items'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $address = $user->address;
        $isNewProfile = !$user->name && (!$address || (!$address->zip && !$address->address && !$address->building));
        return view('mypage.profile', compact('user', 'isNewProfile'));
    }

    public function createProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        if ($user->profile_created) {
            return redirect()->route('profile.edit');
        }
        $validated = $request->validated();
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatarPath = $file->store('avatars', 'public');
            $user->img_url = $avatarPath;
        }
        $user->name = $validated['name'];
        $user->profile_created = true;
        $user->save();
        $address = new Address();
        $address->user_id = $user->id;
        $address->zip = $validated['zip'];
        $address->address = $validated['address'];
        $address->building = $validated['building'];
        $address->save();
        return redirect()->route('home')->with('success', 'プロフィールが作成されました');
    }


    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($user->img_url) {
                Storage::disk('public')->delete($user->img_url);
            }
            $avatarPath = $file->store('avatars', 'public');
            $user->img_url = $avatarPath;
        }
        $user->name = $validated['name'];
        $user->save();
        $address = $user->address ?? new Address();
        $address->user_id = $user->id;
        $address->zip = $validated['zip'];
        $address->address = $validated['address'];
        $address->building = $validated['building'];
        $address->save();
        return redirect()->route('profile')->with('success', 'プロフィールが更新されました');
    }
}
