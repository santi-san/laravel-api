<?php

namespace App\Http\Controllers;

use App\Models\Confirmation;
use Illuminate\Http\Request;
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

    public function addAConfirmation(Request $request) {
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
