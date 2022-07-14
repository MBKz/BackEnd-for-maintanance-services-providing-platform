<?php

namespace App\Http\Interface\Helper;

use Illuminate\Http\Request;


interface CityInterface
{
      public function get_all();
      public function store(Request $request);
      public function update(Request $request,$id);
      public function destroy($id) ;
}