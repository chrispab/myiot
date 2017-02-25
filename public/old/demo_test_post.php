<?php
// use Illuminate\Http\Response;
// use App\Http\Response;

// $fname
// $city
$fname = $_POST["name"];
$city=$_POST["city"];
// $task = {
//     'name'=> 'me',
//     'city'=> 'lug'
// };
return response()->json('$fname');
//echo $city;
//echo $fname;
//var_dump($city, $fname)
?>
