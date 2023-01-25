<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MAsql01 extends Model {
    protected $connection='masql01';
    protected $table='simply_request_from_poste';
    protected $primaryKey='id';
    public $timestamps =false;

    public function getToReport($startDate, $endDate){
        $sql = "SELECT DATE_FORMAT(cont.async_response_date,'%Y%m')
        BILLING_PERIOD,
        '4866006'
        NUMERAZIONE,
        'PosteMobile'
        SERVING_PROVIDER,
        'H3G'
        ACCESS_PROVIDER,
        'BancoPosta'
        CSP_NAME,
        cont.mt_id
        TSOID,
        cont.mt_target                                   MSISDN,
        'Servizi Informativi BancoPosta e Postepay'
        SERVICE_NAME,
        cont.mt_servtype                                 SERVICE_ID,
        REPLACE(cprice.ValueGWProp,'.',',')              IMPORTO,
        if (cont.async_response= 0,'OK', 'KO')           STATO,
        cont.async_response                              CODICE_ERRORE,
        error.DescErrorCode                              DESCRIZIONE_ERRORE
        FROM
        simply_request_from_poste cont
        LEFT JOIN tab_gateway_property cprice  ON  cprice.ContentTypeGWProp = cont.mt_servtype
            AND cprice.CarrierGWProp = '950'
            AND  cprice.ProviderGWProp = '836'
            AND cprice.NameGWProp = 'price.customer'
        INNER JOIN tab_provider prov  ON  prov.CodProv = '836'
        LEFT JOIN tab_error_code error  ON  error.ValueErrorCode = cont.async_response
        WHERE
        cont.async_response_date >='{$startDate}'
        AND cont.async_response_date <='{$endDate}'
        AND cont.used_carrier = 'H3G'
        AND cont.mt_target NOT IN ('393317141590','393774418716','393345449194','393939686174','393895442084','393927723020')
        AND cont.mt_id not like '%test%' ";

        $db         = parent::getConnection()->getPdo();
        $query      = $db->prepare($sql);
        $query->execute();
        return $query;
    }


}
