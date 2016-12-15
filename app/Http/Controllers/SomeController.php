<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SomeController extends Controller
{
    //
    public function testfunction( Request $request)
    {
        if ($request->isMethod('post')){
            return response()->json(['response' => 'This is post method']);
        }
        //\Response::json( ['response' => 'This is get method'] );
        //echo "about to return data from get";
        //die();
        return response()->json(['response' => 'This is get method YAY']);
        //return (['response' => 'This is get method']);

    }
}
