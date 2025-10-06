<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['required_with:password', 'nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'El nombre es requerido',
            'username.required' => 'El nombre de usuario es requerido',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'Por favor ingrese un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está en uso',
            'current_password.required_with' => 'La contraseña actual es requerida para cambiar la contraseña',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
        ]);

        // Check current password if trying to change password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'La contraseña actual es incorrecta'])
                    ->withInput();
            }
        }

        // Update basic information
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado exitosamente');
    }
} 