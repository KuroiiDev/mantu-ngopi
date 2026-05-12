<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetRequestController extends Controller
{
    public function update(Request $request, PasswordResetRequest $passwordResetRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($request->status === 'approved') {
            $passwordResetRequest->user->update([
                'password' => $passwordResetRequest->new_password,
            ]);
        }

        $passwordResetRequest->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Request berhasil diproses!');
    }
}