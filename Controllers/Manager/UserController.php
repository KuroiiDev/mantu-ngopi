<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $users = User::all();
    return view('manager.users.index', compact('users'));
}
    public function create()
{
    return view('manager.users.create');
}
    public function store(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|unique:users',
        'fullname' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:manager,cashier,logistic',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    User::create($validated);

    return redirect()->route('manager.users.index')
    ->with('success', 'Akun berhasil dibuat!');
}
    public function show(User $user)
{
    $passwordRequests = $user->passwordResetRequests()->latest()->get();
    return view('manager.users.show', compact('user', 'passwordRequests'));
}
    public function edit(User $user)
{
    return view('manager.users.edit', compact('user'));
}
public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'username' => 'required|string|unique:users,username,' . $user->id,
        'fullname' => 'required|string',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:manager,cashier,logistic',
    ]);
}
}