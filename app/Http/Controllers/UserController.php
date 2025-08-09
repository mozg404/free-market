<?php

namespace App\Http\Controllers;

use App\Data\User\UserShortData;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        return UserShortData::from($user);
    }
}
