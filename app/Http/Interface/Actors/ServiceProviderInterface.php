<?php
namespace App\Http\Interface\Actors;

use Illuminate\Http\Request;

interface ServiceProviderInterface
{
      public function getAllServiceProvider();
      public function getProviderActivited();
      public function getProviderUnActive();
      public function getProviderBlocked();
      public function getProviderRequests();
      public function AcceptProvider(Request $request,$id);
      public function swichActivitProvider(Request $request);
}