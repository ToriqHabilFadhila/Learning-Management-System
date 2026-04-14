<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

class PageController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function forgot()
    {
        return view('auth.forgot-password', ['mode' => 'request']);
    }

    public function bukaKelas($id)
    {
        $kelas = Classes::findOrFail($id);
        return view('guru.kelas', compact('kelas'));
    }
}
