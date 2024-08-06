<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard(){
        return response([
            'message'=> 'Dashboard'
        ],200);
    }
}
