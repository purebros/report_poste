<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMTDeliveryStatusNotification extends Model {
    protected $connection='iliad';
    protected $table='SmsMTDeliveryStatusNotification';
    protected $primaryKey='IdSmsMTDeliveryStatusNotification';

    protected $fillable = [
        'tx_id',
        'sid',
        'msg_id',
        'delivery_status',
        'op_response_code',
        'op_response_message',
        'InsertDate',
        'UpdateDate',
        'IdState',
    ];
}
