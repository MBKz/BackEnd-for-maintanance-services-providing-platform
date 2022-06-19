<?php

namespace App\Http\Interface\Auth;


interface LoginInterface
{
      public function loginAdmin();
      public function loginServiceProvider();
      public function loginClient();
}
