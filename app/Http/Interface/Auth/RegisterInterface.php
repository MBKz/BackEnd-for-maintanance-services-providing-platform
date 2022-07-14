<?php


namespace App\Http\Interface\Auth;

use Illuminate\Http\Request;

interface RegisterInterface
{
      public function registerServiceProvider(Request $request);
      public function registerClient(Request $request);
}
