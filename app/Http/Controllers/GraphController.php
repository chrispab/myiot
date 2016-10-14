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

      $tSPhi = 24.3;
      $tSPlo = 16.9;

      $settings = [
          'tSPhi' => $tSPhi,
          'tSPlo' => $tSPlo,
          'tmax' => $tmax,
          'tmin'=> $tmin
      ];
      // calc actual start sample id
      // $start_sample_time = last_t - start_t
      //$samples = DB::table('thdata')->all();
      //$samples = DB::table('thdata')->paginate(30);
      //return $samples;
      //$samples[]
    //   {{-- ['name' => 'Samantha'] --}}
    //

    //$tmax = $tmax->('temperature');
    //echo $tmax['temperature'];
    //var_dump($samples);
    //echo '<pre>';
    //$array = (array) $tmax;
    //die(var_dump( $hours ) );
    //echo '</pre>';


        //return view('graph', ['samples' => $samples], compact('hours', 'settings'));
        //return view('graph', ['samples' => $samples], compact('hours', 'settings'));
        return view('graph', compact('samples', 'hours', 'settings'));
        //return View::make('graph', compact('samples', 'hours', 'settings'));
    }
}
