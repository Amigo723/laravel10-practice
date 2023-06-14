<?php

namespace App\Http\Controllers;

use App\Models\Property;

class DashboardController extends Controller
{
    
    //

    public function dashboard(){
        // dd($response->json());
        $response = Property::all();
        return $response;
    }
}
