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
        $type               = $request->header('type');
        $txId               = $request->header('tx-id');
        $sid                = $request->header('sid');
        $msgId              = $request->header('msg-id');
        $deliveryStatus     = $request->header('delivery-status');
        $opResponseCode     = $request->header('op-response-code');
        $opResponseMessage  = $request->header('op-response-message');

        $smsMT              = SmsMT::getMessageById($txId);
        if(!$smsMT){
            return 'ok';
        }
        $smsMT->msg_id                  = $msgId;
        $smsMT->delivery_status         = $deliveryStatus;
        $smsMT->op_response_code        = $opResponseCode;
        $smsMT->op_response_message     = $opResponseMessage;
        $smsMT->save();

        SmsMTDeliveryStatusNotification::create([
            'tx_id'                 => $txId,
            'sid'                   => $sid,
            'msg_id'                => $msgId,
            'delivery_status'       => $deliveryStatus,
            'op_response_code'      => $opResponseCode,
            'op_response_message'   => $opResponseMessage,
            ]
        );
        $shortNumberServiceType = ShortNumberServiceType::getByServiceType($smsMT->servicetype);

        /** Notify Simply */
        $curl = curl_init();
        $errorCode = Constants::SUCCESS;
        if(!in_array($deliveryStatus, ['ACCEPTED_BY_OP', 'DELIVERED_TO_DEV'])){
            $errorCode= Constants::CARRIER_CONNECTING_ERROR;
        }

        $url =$shortNumberServiceType->SmsMTResponseUrl.'?mt_id='.$smsMT->mt_id.'&ar_errorcode='.$errorCode;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            Log::error('NotificationController mt, curl to Simply', ['request'=>$request->all(), 'response'=>$response, 'error'=>$errorCode]);
        } else {
            Log::info('NotificationController mt, curl to Simply', ['request'=>$request->all(), 'response'=>$response]);
        }
        return $this->SUCCESS;
   }

   public function mo(Request $request){
        Log::info('NotificationController mo, init', ['request'=>$request->all()]);

   }
}
