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
    protected $SFTPPATH="/Report";

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

        $fileNameMerge          = 'PUREBROS_'.date('YmdHis');
        $srcMerge               = storage_path("app/public/{$fileNameMerge}.csv");

        $fileNameVodafone       = 'PUREBROS_'.date('YmdHis').'Vodafone';
        $srcVodafone            = storage_path("app/public/{$fileNameVodafone}.csv");
        $this->csvVodafone($startDate, $endDate, $srcVodafone, $srcMerge);

        $fileNameTIM            = 'PUREBROS_'.date('YmdHis').'TIM';
        $srcTim                 = storage_path("app/public/{$fileNameTIM}.csv");
        $this->csvTim($startDate, $endDate, $srcTim, $srcMerge);

        $fileNameMAsql01        = 'PUREBROS_'.date('YmdHis').'MAsql01';
        $srcMAsql01             = storage_path("app/public/{$fileNameMAsql01}.csv");
        $this->csvMSql01($startDate, $endDate, $srcMAsql01, $srcMerge);

        $fileNameIliad          = 'PUREBROS_'.date('YmdHis').'ILIAD';
        $srcIliad               = storage_path("app/public/{$fileNameIliad}.csv");
        $this->csvIliad($startDate, $endDate, $srcIliad, $srcMerge);

        /** Copy files to client */
        $connectionClient = ssh2_connect($this->SFTPHOST, $this->SFTPPORT);
        ssh2_auth_password($connectionClient, $this->SFTPUSER, $this->SFTPPASS);
        $resSFTP = ssh2_sftp($connectionClient);
        $resFile = fopen("ssh2.sftp://{$resSFTP}".$this->SFTPPATH."/{$fileNameMerge}.csv", 'w');
        $srcFile = fopen($srcMerge, 'r');
        stream_copy_to_stream($srcFile, $resFile);

        /** Copy files to archived */
        rename($srcVodafone, "/usr/local/bwms/jobs/report_monthly_Poste_php/reports/archived/{$fileNameVodafone}.csv");
        rename($srcTim, "/usr/local/bwms/jobs/report_monthly_Poste_php/reports/archived/{$fileNameTIM}.csv");
        rename($srcMAsql01, "/usr/local/bwms/jobs/report_monthly_Poste_php/reports/archived/{$fileNameMAsql01}.csv");
        rename($srcIliad, "/usr/local/bwms/jobs/report_monthly_Poste_php/reports/archived/{$fileNameIliad}.csv");
        rename($srcMerge, "/usr/local/bwms/jobs/report_monthly_Poste_php/reports/archived/{$fileNameMerge}.csv");

    }

    public function csvIliad($startDate, $endDate, $srcFile, $srcFileMerge){
        Log::info('Iliad:GeneratedReport start process');
        $smsMt          = new SmsMT();
        $query          = $smsMt->getToReport($startDate, $endDate);
        $file           = fopen($srcFile, 'w');
        $fileMerge      = fopen($srcFileMerge, 'a+');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data['Error_Description']= isset(Constants::TEXT_ERROR[$data['Error_code']]) ? Constants::TEXT_ERROR[$data['Error_code']] : Constants::TEXT_ERROR[99];
            if($data['Error_Description']== Constants::TEXT_ERROR[99] ){
                $data['Error_code']=Constants::INTERNAL_ERROR;
            }
            fwrite($file, implode(";", $data)."\r\n");
            fwrite($fileMerge, implode(";", $data)."\r\n");
        }
        fclose($file);
        fclose($fileMerge);
        Log::info('Iliad:GeneratedReport end process', ['file'=>$srcFile, 'fileMerge'=>$srcFileMerge]);
    }

    public function csvVodafone($startDate, $endDate, $srcFile, $srcFileMerge){
        Log::info('Vodafone:GeneratedReport start process');
        $smsMt          = new Vodafone();
        $this->makeCsvGeneric($smsMt, $startDate, $endDate, $srcFile, $srcFileMerge);
        Log::info('Vodafone:GeneratedReport end process', ['file'=>$srcFile, 'fileMerge'=>$srcFileMerge]);
    }

    public function csvTim($startDate, $endDate, $srcFile, $srcFileMerge){
        Log::info('Tim:GeneratedReport start process');
        $smsMt          = new Tim();
        $this->makeCsvGeneric($smsMt, $startDate, $endDate, $srcFile, $srcFileMerge);
        Log::info('Tim:GeneratedReport end process', ['file'=>$srcFile, 'fileMerge'=>$srcFileMerge]);
    }

    public function csvMSql01($startDate, $endDate, $srcFile, $srcFileMerge){
        Log::info('MSql01:GeneratedReport start process');
        $smsMt          = new MAsql01();
        $this->makeCsvGeneric($smsMt, $startDate, $endDate, $srcFile, $srcFileMerge);
        Log::info('MSql01:GeneratedReport end process', ['file'=>$srcFile, 'fileMerge'=>$srcFileMerge]);
    }

    public function makeCsvGeneric($smsMt, $startDate, $endDate, $srcFile, $srcFileMerge){
        $query          = $smsMt->getToReport($startDate, $endDate);
        $file           = fopen($srcFile, 'w');
        $fileMerge      = fopen($srcFileMerge, 'a+');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            fwrite($file, implode(";", $data)."\r\n");
            fwrite($fileMerge, implode(";", $data)."\r\n");
        }
        fclose($file);
        fclose($fileMerge);
    }
}
