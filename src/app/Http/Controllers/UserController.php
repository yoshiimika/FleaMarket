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
        $keyword = $this->getKeyword($request);
        $items = collect();
        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)
                ->when($keyword, function ($query) use ($keyword) {
                    return $query->where('name', 'LIKE', '%' . $keyword . '%');
                })
                ->get();
        } elseif ($page === 'buy') {
            $items = Item::whereHas('purchase', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->when($keyword, function ($query) use ($keyword) {
                    return $query->where('name', 'LIKE', '%' . $keyword . '%');
                })
                ->get();
        }
        return view('mypage.mypage', compact('user', 'page', 'items', 'keyword'));
    }

    private function getKeyword(Request $request)
    {
        $keyword = $request->query('keyword', session('keyword', ''));
        if ($request->has('keyword')) {
            session(['keyword' => $keyword]);
        }
        return $keyword;
    }

    public function editProfile()
    {
        $user = auth()->user();
        $address = $user->address;
        $isNewProfile = !$user->profile_created;
        return view('mypage.profile', compact('user', 'isNewProfile'));
    }

    public function createProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        if ($user->profile_created) {
            return redirect()->route('profile.edit');
        }
        $this->saveUserProfile($user, $request);
        $this->saveUserAddress($user, $request);
        return redirect()->route('home', ['page' => 'mylist'])
            ->with('success', 'プロフィールが作成されました');
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        $this->saveUserProfile($user, $request);
        $this->saveUserAddress($user, $request);
        return redirect()->route('profile')
            ->with('success', 'プロフィールが更新されました');
    }

    private function saveUserProfile($user, $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('avatar')) {
            if ($user->img_url) {
                Storage::disk('public')->delete($user->img_url);
            }
            $user->img_url = $request->file('avatar')->store('avatars', 'public');
        }
        $user->fill([
            'name' => $validated['name'],
            'profile_created' => true,
        ])->save();
    }

    private function saveUserAddress($user, $request)
    {
        $validated = $request->validated();
        $address = $user->address ?? new Address(['user_id' => $user->id]);
        $address->fill([
            'zip' => $validated['zip'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ])->save();
    }
}
