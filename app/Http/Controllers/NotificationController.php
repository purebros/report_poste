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
       $sid        = $request->header('sid');
       $type       = $request->header('type');
       $txId       = $request->header('tx-id');
       $opId       = $request->header('op-id');
       $msisdn     = $request->header('msisdn');
       $text       = $request->header('text');
       $datetime   = $request->header('datetime');

       $user       = 'PUBRO';
       $pass       = '5521wyqv0882qdoa2662exnb';
       $url        = 'https://iliad.purebros.it/iliad-carrier/notification/mo';
       $headers    = array(
           "authorization: Basic ".base64_encode($user.':'.$pass),
           "cache-control: no-cache",
           "datetime: {$datetime}",
           "msisdn: {$msisdn}",
           "op-id: iliad",
           "sid: {$sid}",
           "text: {$text}",
           "tx-id: {$txId}",
           "type: {$type}"
       );
       $curl       = curl_init();
       curl_setopt_array($curl, array(
           CURLOPT_URL             => $url,
           CURLOPT_RETURNTRANSFER  => true,
           CURLOPT_ENCODING        => "",
           CURLOPT_MAXREDIRS       => 10,
           CURLOPT_TIMEOUT         => 30,
           CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST   => "GET",
           CURLOPT_HTTPHEADER      => $headers,
       ));

       $response       = str_replace(array("\r", "\n"), '', curl_exec($curl));
       $err            = curl_error($curl);
       $httpCode       = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       curl_close($curl);
       if($err){
           Log::error('NotificationController mo, curl to ERMES error', ['request'=>$request->all(), 'url'=>$url, 'headers'=>$headers,  'err'=>$err, 'httpCode'=>$httpCode, 'response'=>$response,]);
       }elseif ((int)$httpCode!=200) {
           Log::error('NotificationController mo, curl to ERMES error', ['request'=>$request->all(), 'url'=>$url, 'headers'=>$headers, 'err'=>$err, 'httpCode'=>$httpCode, 'response'=>$response,]);
       } else {
           Log::info('NotificationController mo, curl to ERMES', ['request'=>$request->all(), 'url'=>$url, 'headers'=>$headers, 'httpCode'=>$httpCode]);
       }

       return Constants::SUCCESS;
   }
}
