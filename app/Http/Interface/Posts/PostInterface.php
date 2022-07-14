<?php

namespace App\Http\Interface\Posts;

use Illuminate\Http\Request;

interface PostInterface {

    public function get_all();
    public function store(Request $request);
    public function destroy($id);
}
