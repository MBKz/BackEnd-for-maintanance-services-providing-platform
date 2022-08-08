<?php

namespace App\Http\Interface\Posts;

use Illuminate\Http\Request;

interface PostInterface {

    public function provider_info($id);
    public function store(Request $request);
    public function destroy($id);
}
