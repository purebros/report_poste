<?php

namespace App\Console\Commands;

use App\Models\SmsMT;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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
        $smsMt = new SmsMT();
        $query = $smsMt->getToReport();
        while ($data = $query->fetch(\PDO::FETCH_ASSOC)) {
            $this->info('Data: '. json_encode($data));
        }
    }
}
