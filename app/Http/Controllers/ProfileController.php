<?php

namespace App\Http\Controllers;

use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $r)
    {
        $r->validate(['cpf' => ['nullable', 'string'], 'phone' => ['nullable', 'string']]);
        $r->merge(['cpf' => $r->filled('cpf') ? preg_replace('/\D/', '', $r->cpf) : null, 'phone' => $r->filled('phone') ? preg_replace('/\D/', '', $r->phone) : null]);
        $d = $r->validate(['name' => ['required', 'string', 'max:100'], 'cpf' => ['nullable', new Cpf, Rule::unique('users', 'cpf')->ignore($r->user()->id)], 'phone' => ['nullable', 'regex:/^\d{10,11}$/'], 'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096']]);
        $user = $r->user();
        $old = $user->photo_path;
        $photo = $r->file('photo')?->store('profile-photos', 'local');
        try {
            $user->forceFill(['name' => $d['name'], 'cpf' => $d['cpf'], 'phone' => $d['phone'], 'photo_path' => $photo ?? $old])->save();
        } catch (\Throwable $e) {
            if ($photo) {
                Storage::disk('local')->delete($photo);
            } throw $e;
        }
        if ($photo && $old) {
            Storage::disk('local')->delete($old);
        }

        return redirect()->route('admin.profile')->with('status', 'Perfil atualizado.');
    }

    public function photo(Request $r)
    {
        $path = $r->user()->photo_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
