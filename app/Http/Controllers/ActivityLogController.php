<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(){
        $lastActivitys = Activity::orderby('id', 'desc')->get();
        return view('pages.backend.activitylog.index',compact('lastActivitys'));
    }
    //Modal Create Menu
    public function detail($param){
        $activity = Activity::with('causer')->where('id', '=', $param)->first();
        return view('pages.backend.activitylog.modal.detail',compact('activity'));
    }
}
