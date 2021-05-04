<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Storage;
use App\Package;

class metrc_package extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metrc:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve all metrc packages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $client = new Client([
            'base_uri' => "https://api-ca.metrc.com",
            'timeout' => 2.0,
        ]);
        $headers = [
            'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
            'auth' => ['4w9TaWS-WuFYilK5r91lmKC2LwuGHr0q0nkvYM3axVo1z1Fo', 'IgLHZR3M-5DjNPsXinfVZJ7PWQfm31CxGD8aFC8dZMbzJP5i'],
        ];
        $license = 'C11-0000224-LIC';
        $response = $client->request('GET', '/packages/v1/active?licenseNumber=' . $license, $headers);
        $packages = json_decode($response->getBody()->getContents());
    //   dd($packages);
        $this->info($packages);
        DB::table('metrc_packages')->truncate();

        for ($i = 0; $i < count($packages); $i++) {
            Package:: updateOrCreate(
                ['ext_id' => $packages[$i]->Id],
                [
                    'ext_id' => $packages[$i]->Id,
                    'tag' => $packages[$i]->Label,
                    'item' => $packages[$i]->ProductName,
                    'category' => $packages[$i]->ProductCategoryName,
                    'item_strain' => $packages[$i]->ItemStrainName,
                    'quantity' => $packages[$i]->Quantity,
                    'lab_testing' => $packages[$i]->InitialLabTestingState,
                    'date' => $packages[$i]->PackagedDate,
           ]);
        }
        $this->info(date_format(date_create(), 'Y-m-d H:i:s'));

    }
}
