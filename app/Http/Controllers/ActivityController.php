<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityController extends ApiController
{
    public function getActivities() {
        $data = [];

        $activities = DB::table('activities')
                ->select(
                        'activities.id', 
                        'activities.name', 
                        'activities.photo', 
                        'activities.date'
                        )
                ->get(); 
        $data['activities'] = $activities;

        return $this->sendResponse($data, 'List of activities');
    }
}
