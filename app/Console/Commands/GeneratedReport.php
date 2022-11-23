<?php

namespace App\Console\Commands;

use App\Models\SmsMT;
use App\Utils\Constants;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GeneratedReport extends Command {

    protected $signature = 'Iliad:GeneratedReport';
    protected $description = 'Generates a CSV file with the uploaded MTS. After saving the file on the target machine';
    protected $force = false;
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        Log::info('Iliad:GeneratedReport start process');
        $smsMt = new SmsMT();
        $query = $smsMt->getToReport();
        $fileName       = 'PUREBROS_'.date('YmdHis').'ILIAD';
        $csvSrc         = storage_path("app/public/{$fileName}.csv") ;
        $file           = fopen($csvSrc, 'w');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data['Error_Description']= isset(Constants::TEXT_ERROR[$data['Error_code']]) ? Constants::TEXT_ERROR[$data['Error_code']] : Constants::TEXT_ERROR[99];
            if($data['Error_Description']== Constants::TEXT_ERROR[99] ){
                $data['Error_code']=Constants::INTERNAL_ERROR;
            }
            fwrite($file, implode(";", $data)."\r\n");
        }
        fclose($file);
        $connection = ssh2_connect('10.10.2.150', 22);
        ssh2_auth_password($connection, 'root', 'Dy64@ih!2mpQ_C7j');
        ssh2_scp_send($connection, $csvSrc, "/usr/local/bwms/jobs/report_mensile_Poste/reports/archived/{$fileName}.csv", 0644);
        unset($csvSrc);
        Log::info('Iliad:GeneratedReport end process');
    }
}
