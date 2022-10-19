<?php

namespace App\Console\Commands;

use App\Models\SmsMT;
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
        $csvSrc         = storage_path("app/public/{$fileName}") ;
        $file           = fopen($csvSrc, 'w');
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            fwrite($file, implode(";", $data)."\r\n");
        }
        fclose($file);
        $connection = ssh2_connect('10.10.2.150', 22);
        ssh2_auth_password($connection, 'root', 'Dy64@ih!2mpQ_C7j');
        ssh2_scp_send($connection, $csvSrc, "/usr/local/bwms/jobs/report_mensile_Poste/reports/archived/{$fileName}", 0644);
        unset($csvSrc);
        Log::info('Iliad:GeneratedReport end process');
    }
}
