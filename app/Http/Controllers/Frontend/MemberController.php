<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function dashboard(): View
    {
        return view('member.dashboard', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
        ]);

        $user->update($validated);

        return redirect()->route('member.dashboard')->with('status', '资料已更新。');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('member.dashboard')->with('status', '密码已更新。');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $user->addMedia($request->file('avatar'))->toMediaCollection('avatar');

        return redirect()->route('member.dashboard')->with('status', '头像已更新。');
    }
}
