<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Sale;
use App\Delivery;
use App\DeliveryStatus;
use App\GeneralSetting;
use DB;

class ApiOzone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozoneapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update deliveries from Ozone API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $api_id = $lims_general_setting_data->api_id;
        $api_key = $lims_general_setting_data->api_key;
        $lims_sale_data = Sale::where('export', true)
                                ->whereNotIn('delivery_status', [4, 10, 11, 12])
                                ->whereNotNull('delivery_status')
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
        $total_sales = $lims_sale_data->count();
        
        // foreach ($lims_sale_data as $key => $sale) {
        //     Log::info("Sale reference : {id}", ['id' => $sale->reference_no]);
        // }

        $done_sales = 0;

        foreach ($lims_sale_data as $key => $sale) {
            if (($api_id !== null) && ($api_key !== null)) {
                $response = Http::asForm()->post('https://api.ozonexpress.ma/customers/' . $api_id . '/' . $api_key . '/tracking', [
                    'tracking-number' => $sale->reference_no,
                ])->json();

                if ((strcmp($response["CHECK_API"]["RESULT"], "SUCCESS") == 0) && (strcmp($response["TRACKING"]["RESULT"], "SUCCESS") == 0)) {
                    $lims_delivery_data = Delivery::where('sale_id', $sale->id)->first();
                    if ($lims_delivery_data){
                        $lims_delivery_status_data = DeliveryStatus::where('reference_no', $lims_delivery_data->reference_no)->get();
                    } else {
                        Log::critical("DR reference not found for sale : {id}", ['id' => $sale->id]);
                        continue;
                    }  
                    
                    if (array_key_exists("HISTORY", $response["TRACKING"])) {
                        foreach ($lims_delivery_status_data as $delivery_status) {
                            $delivery_status->delete();
                        }
    
                        foreach ($response["TRACKING"]["HISTORY"] as $key => $result) {
                            if ($result["STATUT"] !== null) {
                                $deliveries = new DeliveryStatus();
                                $deliveries->reference_no = $lims_delivery_data->reference_no;
                                $lims_statuses_data = DB::table('statuses')->where('status_name', $result["STATUT"])->first();
                                $deliveries->status = $lims_statuses_data->status_id;
                                $deliveries->status_date = $result["TIME_STR"];
                                $deliveries->comment = $result["COMMENT"];
                                $deliveries->save();
                            }
                        }
                    } else {
                        Log::critical('No statuses found : {id}', ['id' => $sale->reference_no]);
                        continue;
                    }

                    $lims_statuses_data = DB::table('statuses')->where('status_name', $response["TRACKING"]["LAST_TRACKING"]["STATUT"])->first();
                    $sale->delivery_status = $lims_statuses_data->status_id;
                    $sale->save();

                    if ($lims_statuses_data->status_id == 4) {
                        $lims_delivery_data->is_close = 1;
                        $lims_delivery_data->save();
                    } else {
                        $lims_delivery_data->is_close = 0;
                        $lims_delivery_data->save();
                    }

                    $returned_statuses = array(10, 11, 12);

                    if (in_array($lims_statuses_data->status_id, $returned_statuses)) {
                        $lims_delivery_data->returned = 1;
                        $lims_delivery_data->save();
                    } else {
                        $lims_delivery_data->returned = 0;
                        $lims_delivery_data->save();
                    }
                    $done_sales++;
                    Log::info('Done : {sale_number}', ['sale_number' => $sale->reference_no]);
                } else {
                    if (strcmp($response["CHECK_API"]["RESULT"], "SUCCESS") !== 0) {
                        Log::critical("Invalid API data");
                    } elseif (strcmp($response["TRACKING"]["RESULT"], "SUCCESS") !== 0) {
                        Log::critical('Invalid Tracking number : {id}', ['id' => $sale->reference_no]);
                    } 
                }
            } else {
                Log::critical("No API data saved");
            }
        }
        Log::info('Number of deliveries updates : {counter}/{total}', ['counter' => $done_sales, 'total' => $total_sales]);
        // Log::info('Number of deliveries updates : {total}', ['total' => $total_sales]);
        Log::info('===================================================');
    }
}
