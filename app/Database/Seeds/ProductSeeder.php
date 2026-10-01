<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // 01 Donat Chair
            [
                'name' => 'Bean Bag Donat Chair',
                'slug' => 'bean-bag-donat-chair',
                'description' => 'Bean bag model donat yang nyaman untuk duduk santai dengan bentuk melingkar yang mendukung posisi tubuh. Cocok untuk ruang keluarga atau kamar.',
                'price' => 525000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 02 Dice Box
            [
                'name' => 'Bean Bag Dice Box Kotak Dadu',
                'slug' => 'bean-bag-dice-box-kotak-dadu',
                'description' => 'Bean bag berbentuk kotak seperti dadu, memberikan dudukan stabil dan nyaman. Ideal untuk ruang santai atau dekorasi modern.',
                'price' => 285000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 03 Floating XL / L / M
            [
                'name' => 'Bean Bag Floating XL',
                'slug' => 'bean-bag-floating-xl',
                'description' => 'Bean bag Floating ukuran XL dengan permukaan lebar, cocok untuk rebahan penuh. Nyaman untuk rileks di ruangan besar.',
                'price' => 800000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Floating L',
                'slug' => 'bean-bag-floating-l',
                'description' => 'Bean bag Floating ukuran L dengan desain ergonomis untuk duduk santai maupun setengah rebahan.',
                'price' => 750000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Floating M',
                'slug' => 'bean-bag-floating-m',
                'description' => 'Bean bag Floating ukuran M, compact dan mudah dipindahkan. Tetap nyaman untuk duduk santai di ruangan kecil.',
                'price' => 700000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 04 Long Chair
            [
                'name' => 'Bean Bag Long Chair',
                'slug' => 'bean-bag-long-chair',
                'description' => 'Bean bag panjang untuk posisi rebahan penuh. Memberikan kenyamanan maksimal untuk istirahat dan relaksasi.',
                'price' => 910000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 05 New Long Chair
            [
                'name' => 'Bean Bag New Long Chair',
                'slug' => 'bean-bag-new-long-chair',
                'description' => 'Versi terbaru dari Long Chair dengan desain yang ditingkatkan, memberikan support lebih baik untuk seluruh tubuh.',
                'price' => 985000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 06 Alpukat Chair
            [
                'name' => 'Bean Bag Alpukat Chair',
                'slug' => 'bean-bag-alpukat-chair',
                'description' => 'Bean bag bentuk alpukat yang unik dan ergonomis. Nyaman digunakan untuk duduk santai berjam-jam.',
                'price' => 575000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 07 Medium & Small Chair
            [
                'name' => 'Bean Bag Medium Chair',
                'slug' => 'bean-bag-medium-chair',
                'description' => 'Bean bag ukuran medium untuk kenyamanan duduk yang pas, cocok untuk remaja dan dewasa.',
                'price' => 425000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Small Chair',
                'slug' => 'bean-bag-small-chair',
                'description' => 'Bean bag ukuran kecil yang ringan dan mudah dipindahkan. Cocok untuk anak-anak dan ruang minimalis.',
                'price' => 335000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 08 Triangle Series (XXL → SSS)
            [
                'name' => 'Bean Bag Triangle XXL 180x100',
                'slug' => 'bean-bag-triangle-xxl-180x100',
                'description' => 'Bean bag Triangle ukuran XXL dengan permukaan super besar untuk rebahan total, cocok untuk ruang keluarga.',
                'price' => 860000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Triangle XL 160x100',
                'slug' => 'bean-bag-triangle-xl-160x100',
                'description' => 'Bean bag Triangle ukuran XL, nyaman untuk duduk santai maupun rebahan setengah badan.',
                'price' => 685000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Triangle L 145x90',
                'slug' => 'bean-bag-triangle-l-145x90',
                'description' => 'Bean bag Triangle ukuran L dengan desain ergonomis dan fleksibel, cocok untuk segala usia.',
                'price' => 580000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Triangle M 135x85',
                'slug' => 'bean-bag-triangle-m-135x85',
                'description' => 'Bean bag Triangle ukuran M yang ringkas namun tetap nyaman untuk duduk santai.',
                'price' => 550000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Triangle S 125x80',
                'slug' => 'bean-bag-triangle-s-125x80',
                'description' => 'Bean bag Triangle ukuran S, ideal untuk ruang kecil namun tetap memberikan kenyamanan maksimal.',
                'price' => 525000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Triangle SS 120x70',
                'slug' => 'bean-bag-triangle-ss-120x70',
                'description' => 'Bean bag Triangle ukuran SS, cocok untuk anak-anak atau tambahan tempat duduk.',
                'price' => 480000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bean Bag Triangle SSS 100x70',
                'slug' => 'bean-bag-triangle-sss-100x70',
                'description' => 'Bean bag Triangle ukuran SSS paling compact. Mudah dipindahkan dan cocok untuk ruang minimalis.',
                'price' => 325000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 09 New Big Chair
            [
                'name' => 'Bean Bag New Big Chair',
                'slug' => 'bean-bag-new-big-chair',
                'description' => 'Bean bag Big Chair versi terbaru dengan kapasitas besar, memberikan kenyamanan ekstra.',
                'price' => 1050000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 10 Big Chair
            [
                'name' => 'Bean Bag Big Chair',
                'slug' => 'bean-bag-big-chair',
                'description' => 'Bean bag ukuran besar yang memberikan ruang duduk lapang untuk relaksasi maksimal.',
                'price' => 925000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 11 Single Chair
            [
                'name' => 'Bean Bag Single Chair',
                'slug' => 'bean-bag-single-chair',
                'description' => 'Bean bag single ergonomis yang nyaman untuk satu orang, cocok untuk kamar atau ruang baca.',
                'price' => 615000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 12 Rock Chair
            [
                'name' => 'Bean Bag Rock Chair',
                'slug' => 'bean-bag-rock-chair',
                'description' => 'Bean bag Rock Chair dengan desain melengkung yang memberikan efek duduk lebih rileks.',
                'price' => 760000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 13 Enjoy Chair
            [
                'name' => 'Bean Bag Enjoy Chair',
                'slug' => 'bean-bag-enjoy-chair',
                'description' => 'Bean bag dengan kenyamanan ekstra untuk bersantai lama. Cocok untuk ruang santai modern.',
                'price' => 775000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 14 Bed Chair
            [
                'name' => 'Bean Bag Bed Chair',
                'slug' => 'bean-bag-bed-chair',
                'description' => 'Bean bag model bed yang bisa digunakan seperti kasur kecil untuk rebahan total.',
                'price' => 850000,
                'stock' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];


        // Insert data ke database
        $this->db->table('products')->insertBatch($data);
    }
}
