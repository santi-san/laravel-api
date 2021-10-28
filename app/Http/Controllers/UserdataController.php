<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userdata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserdataController extends ApiController
{
    public function getUsers() {
        $data = [];
        //$users = Userdata::all(); Eloquent

        //query builder
        $users = DB::table('users')
                ->join('userdatas', 'users.id', '=', 'userdatas.iduser')
                ->select('users.id', 'userdatas.name', 'userdatas.photo', 'userdatas.age', 'userdatas.genre')
                ->get(); 
        $data['users'] = $users;

        return $this->sendResponse($data, 'List of users');
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
