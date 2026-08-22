<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\HealthPost;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Tampilkan form registrasi beserta daftar posyandu yang sudah ada.
     */
    public function showRegistrationForm()
    {
        $healthPosts = HealthPost::orderBy('name')->get();

        return view('auth.register', compact('healthPosts'));
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'registration_type' => ['required', 'in:join,new_posyandu'],
        ];

        if (($data['registration_type'] ?? null) === 'new_posyandu') {
            $rules['posyandu_name'] = ['required', 'string', 'max:255'];
            $rules['posyandu_address'] = ['required', 'string', 'max:255'];
            $rules['posyandu_phone'] = ['nullable', 'string', 'max:20'];
        } else {
            $rules['health_post_id'] = ['required', 'exists:health_posts,id'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (($data['registration_type'] ?? null) === 'new_posyandu') {
            // Mendaftarkan Posyandu baru -> pendaftar langsung jadi Ketua Kader aktif
            $healthPost = HealthPost::create([
                'name' => $data['posyandu_name'],
                'address' => $data['posyandu_address'],
                'phone' => $data['posyandu_phone'] ?? null,
            ]);

            Staff::create([
                'user_id' => $user->id,
                'health_post_id' => $healthPost->id,
                'role' => 'ketua-kader',
                'status' => 'active',
            ]);
        } else {
            // Bergabung ke Posyandu yang sudah ada -> jadi Anggota Kader, menunggu approval Ketua Kader
            Staff::create([
                'user_id' => $user->id,
                'health_post_id' => $data['health_post_id'],
                'role' => 'anggota-kader',
                'status' => 'pending',
            ]);
        }

        return $user;
    }
}