<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Address extends BaseController
{
    public function index()
    {
        $data = [];
        
        return view('dashboard/user/address/index', $data);
    }
}
