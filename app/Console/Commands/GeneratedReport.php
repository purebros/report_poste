<?php

namespace App\Console\Commands;

use App\Models\MAsql01;
use App\Models\SmsMT;
use App\Models\Tim;
use App\Models\Vodafone;
use App\Utils\Constants;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GeneratedReport extends Command {

    protected $signature = 'Iliad:GeneratedReport';
    protected $description = 'Generates a CSV file with the uploaded MTS. After saving the file on the target machine';
    protected $force = false;

    protected $SFTPHOST="213.215.150.141";
    protected $SFTPPORT="44422";
    protected $SFTPUSER="purebros";
    protected $SFTPPASS="Pu_br03";
    protected $SFTPPATH="/Report/";

    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $now = Carbon::now();
        $now->subMonths(1);
        $startDate = $now->format('Y-m-01 00:00:00');
        $endDate = $now->endOfMonth()->format('Y-m-d 23:59:59');

        $fileNameIliad          = 'PUREBROS_'.date('YmdHis').'ILIAD';
        $srcIliad               = storage_path("app/public/{$fileNameIliad}.csv");
        $this->csvIliad($startDate, $endDate, $srcIliad);

        $fileNameVodafone       = 'PUREBROS_'.date('YmdHis').'Vodafone';
        $srcVodafone            = storage_path("app/public/{$fileNameVodafone}.csv");
        $this->csvVodafone($startDate, $endDate, $srcVodafone);

        $fileNameTIM            = 'PUREBROS_'.date('YmdHis').'TIM';
        $srcTim                 = storage_path("app/public/{$fileNameTIM}.csv");
        $this->csvTim($startDate, $endDate, $srcTim);

        $fileNameMAsql01        = 'PUREBROS_'.date('YmdHis').'MAsql01';
        $srcMAsql01             = storage_path("app/public/{$fileNameMAsql01}.csv");
        $this->csvMSql01($startDate, $endDate, $srcMAsql01);

//        $connection = ssh2_connect('10.10.2.150', 22);
//        ssh2_auth_password($connection, 'root', 'Dy64@ih!2mpQ_C7j');
//        ssh2_scp_send($connection, $csvSrc, "/usr/local/bwms/jobs/report_mensile_Poste/reports/archived/{$fileName}.csv", 0644);
//        unset($csvSrc);
        //Log::info('Iliad:GeneratedReport end process');
    }
    public function csvIliad($startDate, $endDate, $csvSrc){
        Log::info('Iliad:GeneratedReport start process');
        $smsMt          = new SmsMT();
        $query          = $smsMt->getToReport($startDate, $endDate);
        $file           = fopen($csvSrc, 'w');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data['Error_Description']= isset(Constants::TEXT_ERROR[$data['Error_code']]) ? Constants::TEXT_ERROR[$data['Error_code']] : Constants::TEXT_ERROR[99];
            if($data['Error_Description']== Constants::TEXT_ERROR[99] ){
                $data['Error_code']=Constants::INTERNAL_ERROR;
            }
            fwrite($file, implode(";", $data)."\r\n");
        }
        fclose($file);
        Log::info('Iliad:GeneratedReport end process',['file'=>$csvSrc]);
    }

    public function csvVodafone($startDate, $endDate, $csvSrc){
        Log::info('Vodafone:GeneratedReport start process');
        $smsMt          = new Vodafone();
        $query          = $smsMt->getToReport($startDate, $endDate);
        $file           = fopen($csvSrc, 'w');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            fwrite($file, implode(";", $data)."\r\n");
        }
        fclose($file);
        Log::info('Vodafone:GeneratedReport end process',['file'=>$csvSrc]);
    }

    public function csvTim($startDate, $endDate, $csvSrc){
        Log::info('Tim:GeneratedReport start process');
        $smsMt          = new Tim();
        $query          = $smsMt->getToReport($startDate, $endDate);
        $file           = fopen($csvSrc, 'w');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            fwrite($file, implode(";", $data)."\r\n");
        }
        fclose($file);
        Log::info('Tim:GeneratedReport end process',['file'=>$csvSrc]);
    }

    public function csvMSql01($startDate, $endDate, $csvSrc){
        Log::info('MSql01:GeneratedReport start process');
        $smsMt          = new MAsql01();
        $query          = $smsMt->getToReport($startDate, $endDate);
        $file           = fopen($csvSrc, 'w');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            fwrite($file, implode(";", $data)."\r\n");
        }
        fclose($file);
        Log::info('MSql01:GeneratedReport end process',['file'=>$csvSrc]);
    }
}
