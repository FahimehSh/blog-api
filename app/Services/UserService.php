<?php

namespace App\Services;

use App\Models\User;

class UserService {
    public function store(array $userData): User
    {
        return User::create([
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'mobile' => $userData['mobile'],
        ]);
    }
}
