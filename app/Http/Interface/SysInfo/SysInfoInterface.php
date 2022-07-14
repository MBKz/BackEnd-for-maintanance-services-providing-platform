<?php

namespace App\Http\Interface\SysInfo;

use Illuminate\Http\Request;


interface SysInfoInterface
{
      public function get_all();
      public function store(Request $request);
      public function update(Request $request,$id);
      public function destroy($id) ;
}