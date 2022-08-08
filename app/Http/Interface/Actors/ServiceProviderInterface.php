<?php
namespace App\Http\Interface\Actors;

use Illuminate\Http\Request;

interface ServiceProviderInterface
{
      public function getAllServiceProvider();
      public function getProviderRequests();
      public function block(Request $request,$id);
      public function unblock(Request $request,$id);
      public function AcceptProvider(Request $request,$id);
}
