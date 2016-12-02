<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;



class GraphController extends Controller
{

    public function __construct()
{
    $this->middleware('auth');
}

    public function home($hours) {
        $minutes = $hours * 60;
        //try {
            $last_record_time = DB::connection('rpimysql')->select('SELECT sample_dt  FROM thdata ORDER BY id DESC LIMIT 1');
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
        $samples = DB::connection('rpimysql')->select('select * from thdata where sample_dt > ?', [$start_time_str]);

        $tmax = DB::connection('rpimysql')->select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmax = $tmax[0]->temperature;
        $tmin = DB::connection('rpimysql')->select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmin = $tmin[0]->temperature;
        //SELECT fields FROM table ORDER BY id DESC LIMIT 1;
        $temp_now = DB::connection('rpimysql')->select('SELECT temperature AS temperature from thdata ORDER BY id DESC LIMIT 1');
        $temp_now = $temp_now[0]->temperature;

        // $tSPhi = DB::connection('rpimysql')->select('SELECT temp_1_sp from settings');
        // $tSPhi = $tSPhi[0]->temp_1_sp;
        // $tSPlo = DB::connection('rpimysql')->select('SELECT temp_0_sp from settings');
        // $tSPlo = $tSPlo[0]->temp_0_sp;


         $tSPhi = 22.5;
         $tSPlo = 15.3;

        $settings = [
            'tSPhi' => $tSPhi,
            'tSPlo' => $tSPlo,
            'tmax' => $tmax,
            'tmin'=> $tmin,
            'temp_now'=> $temp_now,
            'tlast_sample' => $tlast_sample,
            'zone' => 'Zone 1'
        ];
        return view('graph', compact('samples', 'hours', 'settings'));
    }
}
