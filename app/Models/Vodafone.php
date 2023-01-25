<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Vodafone extends Model {
    protected $connection='vodafone';
    protected $table='tab_content';
    protected $primaryKey='id';
    public $timestamps =false;

    public function getToReport($startDate, $endDate){
        $sql = "SELECT DATE_FORMAT(cont.DateTimePostCont,'%Y%m') BILLING_PERIOD,
        sla.ValueGWProp    NUMERAZIONE,
        'PosteMobile'     SERVING_PROVIDER,
        if(car.NameCarrier = 'Vodafone_GIG', 'Vodafone', if(car.NameCarrier='TIM-IT','TIM', car.NameCarrier))   ACCESS_PROVIDER,
        prov.NameProv   CSP_NAME,
        ContentIDCont  TSOID,
        convert (AES_DECRYPT(cont.TargetCont, PbSSO.Get_Crypting_key()),char)  MSISDN,
        sname.ValueGWProp  SERVICE_NAME,
        TypeCont           SERVICE_ID,
        REPLACE(cprice.ValueGWProp,'.',',') IMPORTO,
        if (
        (ErrorCont=0 and CodCarrier in (900,888,740, 950)),'OK',
            if(ErrorCont = 600 and CodCarrier in(666),'OK',
                if(ErrorCont = 0 and CodCarrier in(666),'IN CORSO',
                    if(ErrorCont = 100 and CodCarrier in(440),'IN_CORSO','KO')
                )
            )
        ) STATO,
        cont.ErrorCont      CODICE_ERRORE,
        error.DescErrorCode DESCRIZIONE_ERRORE
        FROM tab_content cont
        LEFT JOIN tab_gateway_property sname ON sname.ContentTypeGWProp = cont.TypeCont
        AND sname.CarrierGWProp = cont.CarrierCont
        AND sname.ProviderGWProp = cont.ProviderCont
        AND sname.NameGWProp = 'service.name'
        LEFT JOIN tab_gateway_property cprice ON cprice.ContentTypeGWProp = cont.TypeCont
        AND cprice.CarrierGWProp = cont.CarrierCont
        AND cprice.ProviderGWProp = cont.ProviderCont
        AND cprice.NameGWProp = 'price.customer'
        LEFT JOIN  tab_gateway_property sla ON sla.ContentTypeGWProp = cont.TypeCont
        AND sla.CarrierGWProp = cont.CarrierCont
        AND sla.ProviderGWProp = cont.ProviderCont
        AND sla.NameGWProp = 'service.la'
        INNER JOIN tab_carrier car ON car.CodCarrier = cont.CarrierCont
        INNER JOIN tab_provider prov ON prov.CodProv = cont.ProviderCont
        LEFT JOIN tab_error_code error ON error.ValueErrorCode = cont.ErrorCont
        where cont.datetimepostcont >='{$startDate}'
        and cont.datetimepostcont <='{$endDate}'
        and cont.datetimeendcont is not null
        and cont.carriercont in (888,666,950,900,740)
        and cont.providercont in ('836')
        and cont.StatusProcMaxCont IN (0,1)
        and cont.targetcont not in ('393317141590','393774418716','393345449194','393939686174','393895442084','393927723020')
        and cont.contentidcont not like '%test%'
        order by CodCont asc";

        $db         = parent::getConnection()->getPdo();
        $query      = $db->prepare($sql);
        $query->execute();
        return $query;
    }


}
