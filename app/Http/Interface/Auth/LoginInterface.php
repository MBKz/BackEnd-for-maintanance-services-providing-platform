<?php

namespace App\Http\Interface\Auth;


use Illuminate\Http\Request;

interface LoginInterface
{
      public function loginAdmin(Request $request);
      public function loginServiceProvider(Request $request);
      public function loginClient(Request $request);
}
