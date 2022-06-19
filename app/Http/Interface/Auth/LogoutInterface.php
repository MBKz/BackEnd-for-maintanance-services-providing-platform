<?php


namespace App\Http\Interface\Auth;

use Illuminate\Http\Request;

interface LogoutInterface
{
      public function logout(Request $request);
}
