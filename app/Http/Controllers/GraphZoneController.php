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

    public function home($zone, $hours)
    {
        if ($zone == 1) {
            $db_conn_str = 'rpimysql';
            $tSPhi = 22.5;
            $tSPhi = DB::connection($db_conn_str)->select('SELECT tempSPLOn  FROM config ORDER BY id DESC LIMIT 1');

            $tSPlo = 15.3;
            $tSPlo = DB::connection($db_conn_str)->select('SELECT tempSPLOff  FROM config ORDER BY id DESC LIMIT 1');

        } elseif ($zone == 2) {
            $db_conn_str = 'pcdmysql';
            $tSPhi = 24.0;
            $tSPlo = 21.0;
        }
        $minutes = $hours * 60;
                //try {
        $last_record_time = DB::connection($db_conn_str)->select('SELECT sample_dt  FROM thdata ORDER BY id DESC LIMIT 1');
        //} catch (\PDOException $e) {
            //show db error message/page
        //    return view('home');
        //}

        $end_time = new \DateTime($last_record_time[0]->sample_dt);
        $tlast_sample = $end_time->format('Y-m-d H:i:s');

        //subtract minutes
        $start_time = $end_time->modify('-'.$minutes.' minutes');


        //$time_diff_string = "2016-10-09 17:02:11";
        $start_time_str =  $start_time->format('Y-m-d H:i:s');
        $samples = DB::connection($db_conn_str)->select('select * from thdata where sample_dt > ?', [$start_time_str]);

        $tmax = DB::connection($db_conn_str)->select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmax = $tmax[0]->temperature;
        $tmin = DB::connection($db_conn_str)->select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmin = $tmin[0]->temperature;
        //SELECT fields FROM table ORDER BY id DESC LIMIT 1;
        $temp_now = DB::connection($db_conn_str)->select('SELECT temperature AS temperature from thdata ORDER BY id DESC LIMIT 1');
        $temp_now = $temp_now[0]->temperature;

        $tSPhi = DB::connection($db_conn_str)->select('SELECT temp_1_sp from settings');
        $tSPhi = $tSPhi[0]->temp_1_sp;
        $tSPlo = DB::connection($db_conn_str)->select('SELECT temp_0_sp from settings');
        $tSPlo = $tSPlo[0]->temp_0_sp;

        $settings = [
            'tSPhi' => $tSPhi,
            'tSPlo' => $tSPlo,
            'tmax' => $tmax,
            'tmin'=> $tmin,
            'temp_now'=> $temp_now,
            'tlast_sample' => $tlast_sample,
            'zone' => 'Zone ' . $zone
        ];
        return view('graph', compact('samples', 'hours', 'settings'));
    }


    public function ajaxgraph($zone, $hours)
    {
        if ($zone == 1) {
          $db_conn_str = 'rpimysql';
          $tSPhi = 22.5;
          $tSPhi = DB::connection($db_conn_str)->select('SELECT tempSPLOn  FROM config ORDER BY id DESC LIMIT 1');

          $tSPlo = 15.3;
          $tSPlo = DB::connection($db_conn_str)->select('SELECT tempSPLOff  FROM config ORDER BY id DESC LIMIT 1');

        } elseif ($zone == 2) {
            $db_conn_str = 'pcdmysql';
            $tSPhi = 24.0;
            $tSPlo = 21.0;
        }
        $minutes = $hours * 60;
                //try {
        $last_record_time = DB::connection($db_conn_str)->select('SELECT sample_dt  FROM thdata ORDER BY id DESC LIMIT 1');
        //} catch (\PDOException $e) {
            //show db error message/page
        //    return view('home');
        //}

        $end_time = new \DateTime($last_record_time[0]->sample_dt);
        $tlast_sample = $end_time->format('Y-m-d H:i:s');

        //subtract minutes
        $start_time = $end_time->modify('-'.$minutes.' minutes');


        //$time_diff_string = "2016-10-09 17:02:11";
        $start_time_str =  $start_time->format('Y-m-d H:i:s');
        $samples = DB::connection($db_conn_str)->select('select * from thdata where sample_dt > ?', [$start_time_str]);

        $tmax = DB::connection($db_conn_str)->select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmax = $tmax[0]->temperature;
        $tmin = DB::connection($db_conn_str)->select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmin = $tmin[0]->temperature;
        //SELECT fields FROM table ORDER BY id DESC LIMIT 1;
        $temp_now = DB::connection($db_conn_str)->select('SELECT temperature AS temperature from thdata ORDER BY id DESC LIMIT 1');
        $temp_now = $temp_now[0]->temperature;

        $tSPhi = DB::connection($db_conn_str)->select('SELECT temp_1_sp from settings');
        $tSPhi = $tSPhi[0]->temp_1_sp;
        $tSPlo = DB::connection($db_conn_str)->select('SELECT temp_0_sp from settings');
        $tSPlo = $tSPlo[0]->temp_0_sp;

        $settings = [
            'tSPhi' => $tSPhi,
            'tSPlo' => $tSPlo,
            'tmax' => $tmax,
            'tmin'=> $tmin,
            'temp_now'=> $temp_now,
            'tlast_sample' => $tlast_sample,
            'zone' => 'Zone ' . $zone
        ];
        return view('ajaxgraph', compact('samples', 'hours', 'settings'));
        //return response()->json( compact('samples', 'hours', 'settings'));
        //return json_encode(compact('samples', 'hours', 'settings')) ;
    }

    public function ajaxgraphall($zone, $hours)
    {
        if ($zone == 1) {
            $db_conn_str = 'rpimysql';
            $tSPhi = 22.5;
            $tSPlo = 15.3;
            //$tSPhi = DB::connection($db_conn_str)->select('SELECT tempSPLOn  FROM config ORDER BY id DESC LIMIT 1');
            //$tSPlo = DB::connection($db_conn_str)->select('SELECT tempSPLOff  FROM config ORDER BY id DESC LIMIT 1');
        } elseif ($zone == 2) {
            $db_conn_str = 'pcdmysql';
            $tSPhi = 24.0;
            $tSPlo = 21.0;
        }
        $minutes = $hours * 60;
                //try {
        $last_record_time = DB::connection($db_conn_str)->select('SELECT sample_dt  FROM thdata ORDER BY id DESC LIMIT 1');

        $end_time = new \DateTime($last_record_time[0]->sample_dt);
        $tlast_sample = $end_time->format('Y-m-d H:i:s');

        //subtract minutes
        $start_time = $end_time->modify('-'.$minutes.' minutes');

        //$time_diff_string = "2016-10-09 17:02:11";
        $start_time_str =  $start_time->format('Y-m-d H:i:s');
        $samples = DB::connection($db_conn_str)->select('select * from thdata where sample_dt > ?', [$start_time_str]);

        $tmax = DB::connection($db_conn_str)->select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmax = $tmax[0]->temperature;
        $tmin = DB::connection($db_conn_str)->select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmin = $tmin[0]->temperature;
        //SELECT fields FROM table ORDER BY id DESC LIMIT 1;
        $temp_now = DB::connection($db_conn_str)->select('SELECT temperature AS temperature from thdata ORDER BY id DESC LIMIT 1');
        $temp_now = $temp_now[0]->temperature;

        // $tSPhi = DB::connection($db_conn_str)->select('SELECT temp_1_sp from settings');
        // $tSPhi = $tSPhi[0]->temp_1_sp;
        // $tSPlo = DB::connection($db_conn_str)->select('SELECT temp_0_sp from settings');
        // $tSPlo = $tSPlo[0]->temp_0_sp;

        $settings = [
            'tSPhi' => $tSPhi,
            'tSPlo' => $tSPlo,
            'tmax' => $tmax,
            'tmin'=> $tmin,
            'temp_now'=> $temp_now,
            'tlast_sample' => $tlast_sample,
            'zone' => 'Zone ' . $zone
        ];
        return view('ajaxgraphall', compact('samples', 'hours', 'settings'));
        //return response()->json( compact('samples', 'hours', 'settings'));
        //return json_encode(compact('samples', 'hours', 'settings')) ;
    }

    public function getajaxgraphdata($zone, $hours)
    {
        if ($zone == 1) {
            $db_conn_str = 'rpimysql';
            $tSPhi = 22.5;
            $tSPlo = 15.3;
            //$tSPhi = DB::connection($db_conn_str)->select('SELECT tempSPLOn  FROM config ORDER BY id DESC LIMIT 1');
            $tSPhi = DB::connection($db_conn_str)->select('SELECT tempSPLOn from config');
            $tSPhi = $tSPhi[0]->tempSPLOn;
            $tSPlo = DB::connection($db_conn_str)->select('SELECT tempSPLOff from config');
            $tSPlo = $tSPlo[0]->tempSPLOff;
            //$tSPlo = DB::connection($db_conn_str)->select('SELECT tempSPLOff  FROM config ORDER BY id DESC LIMIT 1');
            //var_dump(DB::connection($db_conn_str)->select('SELECT tempSPLOn  FROM config ORDER BY id DESC LIMIT 1'));
            //die();

        } elseif ($zone == 2) {
            $db_conn_str = 'pcdmysql';
            $tSPhi = 24.0;
            $tSPlo = 21.0;
        }
        $minutes = $hours * 60;
                //try {
        $last_record_time = DB::connection($db_conn_str)->select('SELECT sample_dt  FROM thdata ORDER BY id DESC LIMIT 1');
        $end_time = new \DateTime($last_record_time[0]->sample_dt);
        $tlast_sample = $end_time->format('Y-m-d H:i:s');

        //subtract minutes
        $start_time = $end_time->modify('-'.$minutes.' minutes');

        //calc start time from Now
        $now_time = new \DateTime();
        $start_time_from_now = $now_time->modify('-'.$minutes.' minutes');


        //$time_diff_string = "2016-10-09 17:02:11";
        //$start_time_str =  $start_time->format('Y-m-d H:i:s');
        $start_time_str =  $start_time_from_now->format('Y-m-d H:i:s');

        $samples = DB::connection($db_conn_str)->select('select * from thdata where sample_dt > ?', [$start_time_str]);

        $tmax = DB::connection($db_conn_str)->select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmax = $tmax[0]->temperature;
        $tmin = DB::connection($db_conn_str)->select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmin = $tmin[0]->temperature;
        //SELECT fields FROM table ORDER BY id DESC LIMIT 1;
        $temp_now = DB::connection($db_conn_str)->select('SELECT temperature AS temperature from thdata ORDER BY id DESC LIMIT 1');
        $temp_now = $temp_now[0]->temperature;

        // $tSPhi = DB::connection($db_conn_str)->select('SELECT temp_1_sp from settings');
        // $tSPhi = $tSPhi[0]->temp_1_sp;
        // $tSPlo = DB::connection($db_conn_str)->select('SELECT temp_0_sp from settings');
        // $tSPlo = $tSPlo[0]->temp_0_sp;
        // $tSPlo = DB::connection($db_conn_str)->select('SELECT temp_0_sp from settings');
        // $tSPlo = $tSPlo[0]->temp_0_sp;
        $settings = [
            'tSPhi' => $tSPhi,
            'tSPlo' => $tSPlo,
            'tmax' => $tmax,
            'tmin'=> $tmin,
            'temp_now'=> $temp_now,
            'tlast_sample' => $tlast_sample,
            'zone' => 'Zone ' . $zone
        ];

        //var_dump( json_decode( compact('samples', 'hours', 'settings')) );
        //die();
        return response()->json( compact('samples', 'hours', 'settings'));
        //return response(compact('samples', 'hours', 'settings'));

        //return json_encode(compact('samples', 'hours', 'settings')) ;
    }
}
