<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMT extends Model {
    protected $connection='iliad';
    protected $table='SmsMT';
    protected $primaryKey='tx_id';
public $timestamps = false;
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

}
