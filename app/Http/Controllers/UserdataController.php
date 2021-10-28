<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userdata;
use Illuminate\Http\Request;


class UserdataController extends ApiController
{
    public function getUsers() {
        $data = [];
        $users = Userdata::all();
        $data['users'] = $users;

        return $this->sendResponse($data, 'List of users');
        /*  return response()->json([
            'data' => $data
        ]); */
    }
    public function getUserDetail($id) {
        //Oauth
        $user = new User();
        //User data
        $userdata = Userdata::where('iduser', $id)->first();//eloquent
        $data = [];
        $data['user'] = $user->find($id);
        $data['userdata'] = $userdata;

        return $this->sendResponse($data, 'User data successfully retrieved');
        /*  return response()->json([
            'data' => $data
        ]); */
    }
}
