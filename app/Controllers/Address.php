<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;

class Address extends BaseController
{
    protected $db, $addressBuilder, $addressModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->addressBuilder = $this->db->table('addresses');
        $this->addressModel = new AddressModel();
    }
    
    public function index()
    {
        $userId = user()->id;
        $query = $this->addressBuilder
            ->select('addresses.*, users.full_name, users.email, users.avatar')
            ->join('users', 'users.id = addresses.user_id')
            ->where('user_id', $userId)
            ->get();
        $addresses = $query->getResultArray();

        $data = [
            'page_title' => 'Dasbor | Alamat',
            'addresses' => $addresses
        ];
        
        return view('dashboard/user/address/index', $data);
    }

    public function show($id)
    {
        $address = $this->addressModel->find($id);

        $data = [
            'page_title' => 'Dasbor | Alamat | ' . $address['label'],
            'address' => $address
        ];
        
        return view('dashboard/user/address/show', $data);
    }
}
