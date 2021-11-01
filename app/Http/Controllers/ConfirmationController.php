<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Confirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OneSignal;
use Illuminate\Support\Facades\Validator;

class ConfirmationController extends ApiController
{
    public function getConfirmations() {
        $data = [];

        $confirmations = Confirmation::all();

        if (!$confirmations) {
            return $this->sendError($confirmations,"There is not confirmations");
        }

        $data['confirmations'] = $confirmations;

        return $this->sendResponse($data, 'List of confirmations');
    }
    public function getConfirmationUser($id, Request $request) {
        $confirmations = Confirmation::where('iduser', $id)
                                    ->get();

        // User
        $confirmations = DB::table('confirmations')
        ->where('confirmations.iduser', '=', $id)
        ->join('activities', 'confirmations.idactivity', 'activities.id')
        ->select('confirmations.id','activities.id AS idactividad', 'activities.name', 'activities.active', 'activities.date', 'activities.description', 'activities.photo')
        ->get();


        $data = [
            'confirmations' => $confirmations
        ];

        return $this->sendResponse($data, 'Confirmations retrieved successfully');

    }

    public function getConfirmationDetail($id, Request $request) {
        // Confirmation
        $confirmation = Confirmation::find($id);

        if($confirmation === null){
            return $this->sendError("Error in the data", ['The confirmation does not exist'] , 422); 
        }


        // Activity 
        $activity = Activity::find($confirmation->idactivity);

        if($activity === null){
            return $this->sendError("Error en los datos", ['La actividad no existe'] , 422); 
        }


        // User
        $users = DB::table('confirmations')
                ->where('confirmations.idactivity', '=', $confirmation->idactivity)
                ->join('userdatas', 'confirmations.iduser', 'userdatas.iduser')
                ->select('userdatas.iduser','userdatas.name','userdatas.photo','userdatas.age','userdatas.genre')
                ->get();

        $data = [
            'activity' => $activity,
            'users' => $users
        ];
        return $this->sendResponse($data, 'Activity retrived successfully');
    }



    public function addConfirmation(Request $request) {
        $validator = Validator::make($request->all(), [
            'iduser' => 'required',
            'idactivity' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),"Error in the validation data",422); 
        }

        $confirmation = Confirmation::where([
            ['iduser', $request->get('iduser')],
            ['idactivity', $request->get('idactivity')]
            ])->first();//eloquent

        if($confirmation !== null) {
            return $this->sendError('Error in the confirmation',["The user has previously confirmed"],422); 
        }
        
        //create activity
        $confirmation = new Confirmation();
        $confirmation->iduser = $request->get('iduser');
        $confirmation->idactivity = $request->get('idactivity');
        $confirmation->save();

         // User
         $users = DB::table('confirmations')
         ->where('confirmations.idactivity', '=', $confirmation->idactivity)
         ->join('userdatas', 'confirmations.iduser', 'userdatas.iduser')
         ->select('userdatas.idonesignal')
         ->get();

         foreach ($users as $user) {
             $id = $user->idonesignal;
             if($id != 1 ) {
                 OneSignal::sendNotificationToUser('another user has signed up for the activity',
                 $id,
                 $url = null,
                 $data = null,
                 $buttons = null,
                 $schedule = null);
             }
         }

        $data = [
            'confirmation' => $confirmation,
        ];
        return $this->sendResponse($data, 'Confirmation created successfully');
    }

    public function deleteConfirmation(Request $request) {
        $confirmation = Confirmation::find($request->get('id'));

        if($confirmation === null){
            return $this->sendError("Error in the data", ['The confirmation does not exist'] , 422); 
        }
        
        $confirmation->delete();

        return $this->sendResponse([
            'status' => 'OK'
        ], 'Confirmation deleted successfully');
    }
}
