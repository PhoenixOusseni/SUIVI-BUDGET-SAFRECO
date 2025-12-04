<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function auth_admin()
    {
        return view('login-admin');
    }

    public function home()
    {
        return view('clients.pages.home');
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function config()
    {
        return view('clients.pages.configs.index');
    }
    
    public function data()
    {
        return view('clients.pages.data.index');
    }
}
