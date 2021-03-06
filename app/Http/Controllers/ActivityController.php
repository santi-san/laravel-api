<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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

    public function getActivityDetail($id) {
        $activity = Activity::find($id);

        if($activity === null) {
            return $this->sendError('Error in the data',['The acitivity does not exist'],422); 
        }

        $confirmations = DB::table('confirmations')
        ->where('confirmations.idactivity', '=', $id)
        ->join('userdatas', 'confirmations.iduser', 'userdatas.iduser')
        ->select('userdatas.iduser','userdatas.name','userdatas.photo','userdatas.age','userdatas.genre')
        ->get();

        $data = [];
        $data['activity'] = $activity;
        $data['user'] = $confirmations;

        return $this->sendResponse($data, 'Activity found');

    }


    public function addActivity(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:activities',
            'photo' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);
        // Si falla la validacion devuelve json con status 422
        if($validator->fails()) {
            return $this->sendError($validator->errors(),"Error en la validacion de datos",422); 
        }
        
        //create activity
        $new_activity = new Activity();
        $new_activity->name = $request->get('name');
        $new_activity->photo = $request->get('photo');
        $new_activity->description = $request->get('description');
        $new_activity->date = $request->get('date');
        $new_activity->save();

        $data = [
            'activity' => $new_activity,
        ];
        return $this->sendResponse($data, 'Activity created successfully');
    }

    public function updateActivity(Request $request) {
        $activity = Activity::find($request->get('id'));

        if($activity === null){
            return $this->sendError("Error en los datos", ['La actividad no existe'] , 422); 
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'photo' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);
        // If validation fails return json with status 422
        if($validator->fails()) {
            return $this->sendError($validator->errors(),"Error en la validacion de datos",422); 
        }

        // Update table Activities

        $activity->name = $request->get('name');
        $activity->photo = $request->get('photo');
        $activity->description = $request->get('description');
        $activity->date = $request->get('date');
        $activity->save();

        $data = [
            'activity' => $activity,
        ];
        return $this->sendResponse($data, 'Activity updated successfully');
    }

    public function deleteActivity(Request $request) {
        $activity = Activity::find($request->get('id'));

        if($activity === null){
            return $this->sendError("Error en los datos", ['La actividad no existe'] , 422); 
        }

        $validator = Validator::make($request->all(), [
            'active' => 'required'
        ]);
        // If validation fails return json with status 422
        if($validator->fails()) {
            return $this->sendError($validator->errors(),"Error en la validacion de datos",422); 
        }

        // Update table Activities

        $activity->active = $request->get('active');
        $activity->save();

        $data = [
            'activity' => $activity,
        ];
        return $this->sendResponse($data, 'Activity updated successfully');
    }
}
