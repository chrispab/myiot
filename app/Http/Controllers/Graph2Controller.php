<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;

class Graph2Controller extends Controller
{
    public function home($hours) {
        $minutes = $hours * 60;
        //try {
            $last_record_time = DB::connection('pcdmysql')->select('SELECT sample_dt  FROM thdata ORDER BY id DESC LIMIT 1');
        // } catch (\PDOException $e) {
        //     //show db error message/page
        //     return view('home');
        // }
        $end_time = new \DateTime($last_record_time[0]->sample_dt);
        $tlast_sample = $end_time->format('Y-m-d H:i:s');
        //subtract minutes
        $start_time = $end_time->modify('-'.$minutes.' minutes');
        //$time_diff_string = "2016-10-09 17:02:11";
        $start_time_str =  $start_time->format('Y-m-d H:i:s');
        //try {
            $samples = DB::connection('pcdmysql')->select('select * from thdata where sample_dt > ?', [$start_time_str]);
        //} catch (\PDOException $e) {
        //    return view('error');
        //}
        $tmax = DB::connection('pcdmysql')->select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmax = $tmax[0]->temperature;
        $tmin = DB::connection('pcdmysql')->select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
        $tmin = $tmin[0]->temperature;
        $tSPhi = 24.0;
        $tSPlo = 21.0;
        $settings = [
            'tSPhi' => $tSPhi,
            'tSPlo' => $tSPlo,
            'tmax' => $tmax,
            'tmin'=> $tmin,
            'tlast_sample' => $tlast_sample,
            'zone' => 'Zone 2'
        ];
        return view('graph', compact('samples', 'hours', 'settings'));
    }
}
