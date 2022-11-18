<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmsMT extends Model {
    protected $connection='iliad';
    protected $table='SmsMT';
    protected $primaryKey='tx_id';
    public $timestamps =false;
    protected $fillable = [
            'mt_id',
            'IdUsers',
            'ShortNumber',
            'MSISDN',
            'categ',
            'delivery_mode',
            'reason',
            'cents',
            'message_text',
            'InsertDate',
            'UpdateDate',
            'IdState',
            'syn_result',
            'syn_reason',
            'msg_id',
            'syn_error_type',
            'syn_op_response_code',
            'syn_op_response_message',
            'delivery_status',
            'op_response_code',
            'op_response_message',
            'servicetype',
    ];

    public static function getMessageByIdAndUser($mtId, $IdUsers){
        return self::where('mt_id', $mtId)->where('IdUsers', $IdUsers)->first();
    }
    public static function getMessageById($mtId){
        return self::where('mt_id', $mtId)->first();
    }
    public function getToReport(){
        $now = Carbon::now();
        $now->subMonths(1);
        $startDate = $now->format('Y-m-01 00:00:00');
        $endDate = $now->endOfMonth()->format('Y-m-d 23:59:59');
        $sql = self::select(
            DB::raw("DATE_FORMAT(InsertDate,'%Y%m') as  BILLING_PERIOD"),
            'ShortNumber as  NUMERAZIONE',
            DB::raw("'PosteMobile' as SERVING_PROVIDER"),
            DB::raw("'ILIAD' as ACCESS_PROVIDER"),
            DB::raw("'BancoPosta' as CSP_NAME"),
            'mt_id as TSOID,',
            'msisdn as  MSISDN',
            'SmsMT.servicetype as  SERVICE_ID',
            DB::raw("'Servizi Informativi BancoPosta e Postepay' as SERVICE_NAME"),
            DB::raw("REPLACE(TRUNCATE((ShortNumberServiceType.price/100),2),'.',',') as IMPORTO"),
            DB::raw("if (syn_result= 'SUCCESS','OK', 'KO') as STATO"),
            DB::raw("if (syn_result= 'SUCCESS','0', syn_result) as Error_code")
        )
            ->whereRaw("SmsMT.InsertDate >= '{$startDate}'")
            ->whereRaw("SmsMT.InsertDate <= '{$endDate}'")
            ->join('ShortNumberServiceType', 'ShortNumberServiceType.ServiceType', 'SmsMT.servicetype')
            ->toSql();

        $db         = parent::getConnection()->getPdo();
        $query      = $db->prepare($sql);
        $query->execute();
        return $query;
    }

}
