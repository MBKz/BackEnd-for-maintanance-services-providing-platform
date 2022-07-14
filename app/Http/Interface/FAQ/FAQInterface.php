<?php

namespace App\Http\Interface\FAQ;

use Illuminate\Http\Request;

interface FAQInterface
{
      public function get_all();
      public function AddQuestion(Request $request);
      public function AddAnswer(Request $request,$id);
}