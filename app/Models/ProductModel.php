<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'is_featured',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'additional_images',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id' => 'permit_empty',
        'name' => 'required|max_length[255]|is_unique[products.name,id,{id}]',
        'description' => 'permit_empty|max_length[1000]',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Nama produk wajib diisi!',
            'max_length' => 'Panjang karakter melebihi dari yang ditentukan!',
            'is_unique' => 'Nama produk sudah digunakan!'
        ],
        'description' => [
            'max_length' => 'Karakter terlalu panjang!'
        ],
        'price' => [
            'required' => 'Harga produk wajib diisi!',
            'numeric' => 'Harga produk harus berupa angka!'
        ],
        'stock' => [
            'required' => 'Stok produk wajib diisi!',
            'integer' => 'Stock produk harus berupa angka!'
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['generateSlug'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['convertJsonToArray'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name'])) {
            $slug = url_title($data['data']['name'], '-', true);
            $data['data']['slug'] = $slug;
        }
        return $data;
    }
    
    protected function convertJsonToArray(array $data)
    {
        if (isset($data['data'])) {
            if (is_array($data['data'])) {
                foreach ($data['data'] as &$item) {
                    if (isset($item['additional_images'])) {
                        $item['additional_images'] = json_decode($item['additional_images'], true);
                    }
                }
            } else {
                if (isset($data['data']['additional_images'])) {
                    $data['data']['additional_images'] = json_decode($data['data']['additional_images'], true);
                }
            }
        }
        return $data;
    }
}
