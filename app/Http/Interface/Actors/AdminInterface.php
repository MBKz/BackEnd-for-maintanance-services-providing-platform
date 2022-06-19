<?php
namespace App\Http\Interface\Actors;

use Illuminate\Http\Request;

interface AdminInterface
{
      public function getAdmins();
      public function CreateAdmin(Request $request);
      public function destroy($id);
}