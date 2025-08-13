<?php

namespace App\Controllers;

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
            'page_title' => "Dasbor | " . in_groups('admin') ? 'Admin' : 'Pengguna' . " | Profil",
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
            'page_title' => "Dasbord | " . in_groups('admin') ? 'Admin' : 'Pengguna' . " | Edit Profil"
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
}
