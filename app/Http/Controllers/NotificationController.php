<?php

namespace App\Http\Controllers;


use App\Models\ShortNumberServiceType;
use App\Models\SmsMT;
use App\Models\SmsMTDeliveryStatusNotification;
use App\Utils\Constants;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class NotificationController extends BaseController {

    public function mt(Request $request){
        Log::info('NotificationController mt, init', ['request'=>$request->all(), 'headers'=>$request->header()]);
        return Constants::SUCCESS;
   }

   public function mo(Request $request){
        Log::info('NotificationController mo, init', ['request'=>$request->all(), 'headers'=>$request->header()]);
       return Constants::SUCCESS;
   }
}
