<?php

namespace App\Controllers;

use Myth\Auth\Password;
use Myth\Auth\Models\UserModel;
use App\Controllers\BaseController;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'page_title' => "Dasbor | " . (in_groups('admin') ? "Admin" : "Pengguna") . " | Profil",
        ];

        if (in_groups('admin')) {
            return view('dashboard/admin/profile/index', $data);
        } else {
            return view('dashboard/user/profile/index', $data);
        }
    }

    public function edit()
    {
        $data = [
            'page_title' => "Dasbor | " . (in_groups('admin') ? "Admin" : "Pengguna") . " | Ubah Profil",
        ];

        if (in_groups('admin')) {
            return view('dashboard/admin/profile/edit', $data);
        } else {
            return view('dashboard/user/profile/edit', $data);
        }
    }

    public function update()
    {
        $user = user();
        $userId = $user->id;
        $postData = $this->request->getPost();
        $avatarFile = $this->request->getFile('avatar');

        $postData['id'] = $userId;

        $rules = $this->userModel->validationRules;
        $rules['id'] = 'permit_empty';

        $rules['email'] = str_replace('{id}', $userId, $rules['email']);
        $rules['username'] = str_replace('{id}', $userId, $rules['username']);

        if (!$this->userModel->validate($postData)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        $updateData = [
            'id' => $userId,
            'full_name' => $postData['full_name'],
            'email' => $postData['email'],
            'username' => $postData['username']
        ];

        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($avatarFile->getExtension(), $allowedExt)) {
                return redirect()->back()->withInput()->with('error_avatar', 'Format gambar tidak valid!');
            }
            if ($avatarFile->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('error_avatar', 'Ukuran gambar terlalu besar! Maksimal 1MB.');
            }
            $avatarName = $avatarFile->getRandomName();
            $avatarFile->move(FCPATH . 'img/uploads/avatar', $avatarName);

            if (!empty($user->avatar) && $user->avatar !== 'default-img-avatar.svg') {
                $oldAvatarPath = FCPATH . 'img/uploads/avatar/' . $user->avatar;
                if (file_exists($oldAvatarPath)) {
                    @unlink($oldAvatarPath);
                }
            }
            $updateData['avatar'] = $avatarName;
        }

        $result = $this->userModel->save($updateData);

        if ($result) {
            if (in_groups('admin')) {
                return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui!');
            } else {
                return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui!');
            }
        } else {
            if (in_groups('admin')) {
                return redirect()->route('admin.profile.index')->with('failed', 'Profil gagal diperbarui!');
            } else {
                return redirect()->route('user.profile.index')->with('failed', 'Profil gagal diperbarui!');
            }
        }
    }

    public function changePassword()
    {
        $data = [
            'pageTitle' => 'Dasbor | Admin | Ganti Sandi'
        ];

        if (in_groups('admin')) {
            return view('dashboard/admin/profile/change-password', $data);
        } else {
            return view('dashboard/user/profile/change-password', $data);
        }
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => [
                'label' => 'Sandi Saat Ini',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'new_password' => [
                'label' => 'Sandi Baru',
                'rules' => 'required|min_length[8]|strong_password',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'strong_password' => '{field} harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.'
                ]
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi Sandi',
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'matches' => '{field} tidak cocok dengan Sandi Baru.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        $userId = user()->id;
        $user = $this->userModel->find($userId);

        if (!Password::verify($currentPassword, $user->password_hash)) {
            return redirect()->back()->with('error', 'Sandi saat ini tidak benar!');
        }

        if (Password::verify($newPassword, $user->password_hash)) {
            return redirect()->back()->with('error', 'Sandi baru tidak boleh sama dengan sandi saat ini!');
        }

        $passwordHash = Password::hash($newPassword);
        $updateData = [
            'id' => $userId,
            'password_hash' => $passwordHash,
            'reset_hash' => null,
            'reset_at' => null,
            'reset_expires' => null,
            'force_pass_reset' => 0
        ];

        if ($this->userModel->save($updateData)) {
            $auth = service('authentication');
            $auth->logout();

            session()->setFlashdata('message', 'Sandi berhasil diperbarui! Silakan login dengan sandi baru.');

            return redirect()->to('/login');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui sandi!');
        }
    }
}
