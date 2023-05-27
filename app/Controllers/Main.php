<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Main extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Sistem Inventory Lab TKJ'
        ];
        return view('main/dashboard', $data);
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Sistem Inventory Lab TKJ'
        ];
        return view('main/dashboard', $data);
    }
}
