<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
        ]);

        auth()->user()->update([
            'fullname' => $request->fullname,
        ]);

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diupdate!');
    }

    // Manager - halaman ganti password langsung
    public function changePassword()
    {
        return view('manager.change-password');
    }

    // Manager - proses ganti password langsung
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai!'
            ]);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile')
            ->with('success', 'Password berhasil diubah!');
    }

    // Kasir & Logistik - halaman request ganti password
    public function requestPassword()
    {
        $requests = PasswordResetRequest::where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('change-password', compact('requests'));
    }

    // Kasir & Logistik - store request ganti password
    public function storeRequest(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $existing = PasswordResetRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->withErrors([
                'new_password' => 'Kamu masih memiliki request yang belum diproses oleh Manager!'
            ]);
        }

        PasswordResetRequest::create([
            'user_id' => auth()->id(),
            'new_password' => $request->new_password,
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Request ganti password berhasil dikirim ke Manager!');
    }
}