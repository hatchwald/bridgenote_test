<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;

class dashboardController extends Controller
{
    public function index()
    {
        $data['user'] = User::all();
        return view('dashboard', $data);
    }

    public function test()
    {
        $data = ['some', 'arr'];
        return json_encode($data);
    }
}
