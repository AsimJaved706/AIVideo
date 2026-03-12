<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Campaign;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::withCount('campaigns')->get();
        $campaigns = Campaign::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('users', 'campaigns'));
    }
}
