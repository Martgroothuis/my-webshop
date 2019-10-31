<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('admin.index');
    }

    public function customers()
    {
        return User::where('type', '=', 'default')->get(['id', 'name', 'email']);
    }
    public function admin()
    {
        return User::where('type', '=', 'admin')->get(['id', 'name', 'email']);
    }
}
