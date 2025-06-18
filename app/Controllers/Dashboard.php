<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function admin(): string
    {
        return view('dashboard/admin/index');
    }

    public function user(): string
    {
        return view('dashboard/user/index');
    }
}
