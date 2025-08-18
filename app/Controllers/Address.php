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
            ->select('addresses.*, full_name, email, avatar, username')
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

    public function store()
    {
        $postData = $this->request->getPost();
        $rules = $this->addressModel->getValidationRules();
        $ruleMessages = $this->addressModel->getValidationMessages();
        $validated = $this->validateData($postData, $rules, $ruleMessages);

        if (!$validated) {
            return $this->response->setJSON([
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $postData['user_id'] = user()->id;

        $this->addressModel->save($postData);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $postData = $this->request->getPost();
        $rules = $this->addressModel->getValidationRules();
        $ruleMessages = $this->addressModel->getValidationMessages();
        $validated = $this->validateData($postData, $rules, $ruleMessages);

        if (!$validated) {
            return $this->response->setJSON([
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $this->addressModel->update($id, $postData);

        $updatedAddress = $this->addressModel->find($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui!',
            'data' => $updatedAddress
        ]);
    }

    public function destroy($id)
    {
        $address = $this->addressModel->find($id);

        if (!$address) {
            return $this->response->setJSON([
                'error' => 'Alamat tidak ditemukan.'
            ])->setStatusCode(404);
        }

        $this->addressModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alamat berhasil dihapus!'
        ]);
    }
}
