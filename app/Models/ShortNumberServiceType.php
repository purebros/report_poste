<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortNumberServiceType extends Model {
    protected $connection='iliad';
    protected $table='ShortNumberServiceType';
    protected $primaryKey='IdShortNumberServiceType';
    public $timestamps =false;
    static function getByServiceType(int $serviceType){
        return self::where('ServiceType', $serviceType)
            ->select(
                'ShortNumberServiceType.*',
                'Users_ShortNumber.IdUsers',
                'Users_ShortNumber.ShortNumber',
                'Users_ShortNumber.SmsMTResponseUrl',
                'Users_ShortNumber.SmsMODeliveryUrl'
            )
            ->join('Users_ShortNumber', 'Users_ShortNumber.IdUsers_ShortNumber', 'ShortNumberServiceType.IdUsers_ShortNumber')->get();
    }
}
