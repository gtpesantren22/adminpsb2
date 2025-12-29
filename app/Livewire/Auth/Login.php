<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $errorMessage;
    public bool $loading = false;

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ];

    public function masuk()
    {
        $this->loading = true;
        $this->validate();

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ])) {

            session()->regenerate(); // ⬅️ WAJIB (keamanan)

            $this->dispatch(
                'swal',
                title: 'Success!!',
                text: 'Login berhasil',
                icon: 'success',
                timer: 1500,
                redirect: url('/')
            );
        }

        $this->errorMessage = 'Email atau password salah';
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}
