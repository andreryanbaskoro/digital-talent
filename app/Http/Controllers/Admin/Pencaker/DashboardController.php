<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Pencari Kerja',
        ];

        return view('admin.pencaker.dashboard.dashboard', $data);
    }
}
