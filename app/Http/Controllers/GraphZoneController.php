<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;

class GraphZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getajaxgraphdata($zone, $hours)
    {
        $db_conn_str = 'zone'.$zone.'mysql';

        //get all config vals
        $config = DB::connection($db_conn_str)->select('SELECT * from config');

        $minutes = (int)((float)$hours * 60.0);

        $last_record = DB::connection($db_conn_str)->select('SELECT * FROM thdata ORDER BY id DESC LIMIT 1');

        $end_time = new \DateTime($last_record[0]->sample_dt);
        $endt = new \DateTime($last_record[0]->sample_dt);
        //$tlast_sample = $end_time->format('Y-m-d H:i:s');
        //subtract minutes for span from end to get start time
        $start_time = $end_time->modify('-'.$minutes.' minutes');

//        //calc start time from Now
//        $now_time = new \DateTime();
//        $start_time_from_now = $now_time->modify('-'.$minutes.' minutes');
//        $start_time_str =  $start_time_from_now->format('Y-m-d H:i:s');

        //get all samples from > start time to last sample
        $start_time_str =  $start_time->format('Y-m-d H:i:s');
        $samples = DB::connection($db_conn_str)->select('select * from thdata where sample_dt > ?', [$start_time_str]);
        //get min and max temp for range of samples
        $min_max = DB::connection($db_conn_str)->select('SELECT MIN(temperature) AS min_temp, MAX(temperature) AS max_temp from thdata where sample_dt > ?', [$start_time_str]);

        // $res = DB::connection($db_conn_str)->select('SELECT temperature AS temperature from thdata ORDER BY id DESC LIMIT 1');
        //$temp_now = $res[0]->temperature;

        //check to see if data is stale
        //$dataStale = false;
        $now_time = new \DateTime();
        $since_start = $now_time->diff($endt);
        // if (( ($since_start->i)) > 5) {
        //     $dataStale = true;
        // }
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        //echo $minutes.' minutes';

        $settings = [
                'tSPhi' => $config[0]->tempSPLOn,
                'tSPlo' => $config[0]->tempSPLOff,
                'tmax' => $min_max[0]->max_temp,
                'tmin'=> $min_max[0]->min_temp,
                'temp_now'=> $last_record[0]->temperature,
                'tlast_sample' => $end_time->format('Y-m-d H:i:s'),
                'zone' => 'Zone ' . $zone,
                'systemUpTime' => $config[0]->systemUpTime,
                'processUptime' => $config[0]->processUptime,
                'systemMessage' => $config[0]->systemMessage,
                'lightState' => $config[0]->lightState,
                'staleMinutes' => $minutes
            ];

        return response()->json(compact('samples', 'hours', 'settings'));
        //return response(compact('samples', 'hours', 'settings'));
        //return json_encode(compact('samples', 'hours', 'settings')) ;
    }
}
