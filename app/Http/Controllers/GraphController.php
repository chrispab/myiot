<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    public function home($hours)
    {
      //calc sample start time based on hors requested and current last sample time
      // $last_sample_dt sample time = get last sample time
      // calc required start sample time from $hours  var
      // echo $start_time = new \DateTime($hours)->;
      $minutes = $hours * 60;
       $end_time = new \DateTime();
      $start_time = $end_time->modify('-'.$minutes.' minutes');
      //date_diff($start_time,$end_time);
      // //echo $time_diff_string = $time_diff->format('Y-m-d H:i:s');
      // $time_no

      $time_diff_string = "2016-10-09 17:02:11";
      $start_time_str =  $start_time->format('Y-m-d H:i:s');

      //echo $start_time_str;

      $samples = DB::select('select * from thdata where sample_dt > ?', [$start_time_str]);
      $tmax = DB::select('SELECT MAX(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
      $tmax = $tmax[0]->temperature;
      $tmin = DB::select('SELECT MIN(temperature) AS temperature from thdata where sample_dt > ?', [$start_time_str]);
      $tmin = $tmin[0]->temperature;

      $tSPhi = 22.5;
      $tSPlo = 16.9;

      $settings = [
          'tSPhi' => $tSPhi,
          'tSPlo' => $tSPlo,
          'tmax' => $tmax,
          'tmin'=> $tmin
      ];

        return view('graph', compact('samples', 'hours', 'settings'));
    }
}
