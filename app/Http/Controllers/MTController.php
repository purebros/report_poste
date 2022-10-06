<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MTController extends BaseController {
   public function sendMT(Request $request){
        Log::info('MTController sendMT, init', ['request'=>$request->all()]);
        $mtUser     = $request->get('mt_user');
        $mtPass     = $request->get('mt_pass');
        $mtFormat   = $request->get('mt_format');//false
        $mtUdh      = $request->get('mt_udh');//false
        $pbMoid     = $request->get('pb_moid');//false
        $pbCcid     = $request->get('pb_ccid');//false
        $mtId       = $request->get('mt_id');
        $mtSource   = $request->get('mt_source');
        $mtTarget   = $request->get('mt_target');
        $mtCarrier  = $request->get('mt_carrier');
        $mtBodyCount= $request->get('mt_bodycount');
        $mtBody1    = $request->get('mt_body1');
        $mtServType = $request->get('mt_servtype');


        $curl = curl_init();
        curl_setopt_array($curl, array(
           CURLOPT_URL => "https://ermes-coll.engds.it/ermes184/serviceEngine",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => "text={$mtBody1}",
           CURLOPT_HTTPHEADER => array(
               "authorization: Basic ".base64_encode($mtUser.':'.$mtPass),
               "cache-control: no-cache",
               "categ: /pubro/test/dquattro",
               "cmd: sendGenericCaringSMS",
               "content-type: application/x-www-form-urlencoded",
               "msisdn: +{$mtTarget}",
               "op-id: {$mtCarrier}",
               "sid: {$mtSource}",
               "tx-id: {$mtId}"
           ),
        ));

        $response = curl_exec($curl);
        Log::info('MTController sendMT, curl to ENGINEERING', ['request'=>$request->all(), 'response'=>$response]);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
           echo "cURL Error #:" . $err;
        } else {
           echo $response;
        }
   }
}
