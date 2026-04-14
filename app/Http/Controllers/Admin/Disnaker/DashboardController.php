<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // dummy data (UI only)
        $data = [
            'title' => 'Dashboard Disnaker',
        ];

        return view('admin.disnaker.dashboard.dashboard', $data);
    }
}
