<?php

namespace App\Http\Interface\Profile;

use Illuminate\Http\Request;

interface ProfileInterface {
    public function editProfile(Request $request);
    public function getProfile();
}