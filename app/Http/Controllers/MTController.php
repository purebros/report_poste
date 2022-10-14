<?php

namespace App\Http\Controllers;


use App\Models\ShortNumberServiceType;
use App\Models\SmsMT;
use App\Utils\Constants;
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

	$shortNumberServiceType = ShortNumberServiceType::getByServiceType($mtServType);
        if(!$shortNumberServiceType){
            return Constants::SERVICE_NOT_FOUND;
        }

        if(count($shortNumberServiceType)>1){
            return Constants::SERVICE_NOT_FOUND;
        }

        $shortNumberServiceType = $shortNumberServiceType[0];
        $hasMessage = SmsMT::getMessageByIdAndUser($mtId, $shortNumberServiceType->IdUsers);
        if($hasMessage){
            return Constants::MT_ID_ALREADY_EXISTED;
        }

        $smsMT = SmsMT::create([
            'mt_id'=>$mtId,
            'IdUsers'=>$shortNumberServiceType->IdUsers,
            'ShortNumber'=>$shortNumberServiceType->ShortNumber,
            'MSISDN'=>$mtTarget,
            'categ'=>$shortNumberServiceType->categ,
            'delivery_mode'=>0,
	        'reason'=>'DEFAULT',
            'cents'=>$shortNumberServiceType->price,
            'message_text'=>$mtBody1,
            'IdState'=>0,
            'syn_result'=>'',
            'syn_reason'=>null,
            'msg_id'=>null,
            'syn_error_type'=>null,
            'syn_op_response_code'=>null,
            'syn_op_response_message'=>null,
            'delivery_status'=>'',
            'op_response_code'=>null,
            'op_response_message'=>null,
            'servicetype'=>$mtServType,
        ]);

        $curl = curl_init();
        $url = "https://ssl.engds.it/ermes-iliad/serviceEngine";

        $cmd = 'cmd:sendGenericCaringSMS';
        $headers = array(
            "authorization: Basic ".base64_encode('PUBRO:5521wyqv0882qdoa2662exnb'),
            "cache-control: no-cache",
            "tx-id: {$smsMT->tx_id}",
            "msisdn: +{$mtTarget}",
            "categ: {$shortNumberServiceType->categ}",
            "op-id: {$mtCarrier}",
            "content-type: application/x-www-form-urlencoded; charset=ISO-8859-15",
            "sid: {$shortNumberServiceType->sid}",
        );
        if($shortNumberServiceType->price>0){
            $cmd = 'cmd:sendBillingSMS';
            $headers[] = "cents: {$shortNumberServiceType->price}";
            $headers[] = "offer-mode: PULL";
        }
        $headers[]=$cmd;
        $headersResponse=[];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "text={$mtBody1}",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => 1,
            CURLOPT_HEADERFUNCTION =>
            function ($curl, $header) use (&$headersResponse) {
               $len = strlen($header);
               $header = explode(':', $header, 2);
               if (count($header) < 2) // ignore invalid headers
                   return $len;

               $headersResponse[strtolower(trim($header[0]))] = trim( preg_replace("/[^a-zA-Z0-9]+/", "", $header[1]));
               return $len;
            }
        ));

        $response       = curl_exec($curl);
        $err            = curl_error($curl);
        $httpCode       = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($err){
            Log::error('MTController sendMT, curl to ENGINEERING error', ['request'=>$request->all(), 'url'=>$url, 'headers'=>$headers, 'response'=>$response, 'headersResponse'=>$headersResponse, 'err'=>$err, 'httpCode'=>$httpCode]);
            $smsMT->syn_result = Constants::CARRIER_CONNECTING_ERROR;
            $smsMT->syn_reason = 'ERROR_CALL_CARRIER';
            $smsMT->save();
            return Constants::CARRIER_CONNECTING_ERROR;
        }elseif ((int)$httpCode!=200) {
            Log::error('MTController sendMT, curl to ENGINEERING error', ['request'=>$request->all(), 'url'=>$url, 'headers'=>$headers, 'response'=>$response, 'headersResponse'=>$headersResponse, 'err'=>$err, 'httpCode'=>$httpCode]);
            $smsMT->syn_result  = $headersResponse['result'];
            $smsMT->syn_reason  = 'ERROR_CALL_CARRIER';
            $smsMT->save();
            return Constants::CARRIER_CONNECTING_ERROR;
        } else {
            Log::info('MTController sendMT, curl to ENGINEERING', ['request'=>$request->all(), 'url'=>$url, 'headers'=>$headers, 'headersResponse'=>$headersResponse,  'httpCode'=>$httpCode]);
            $smsMT->syn_result  = $headersResponse['result'];
            $smsMT->msg_id      = $headersResponse['msg-id'];
            $smsMT->save();
            return Constants::SUCCESS;
        }
   }
}
