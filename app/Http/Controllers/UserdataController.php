<?php

namespace App\Http\Controllers;

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
}
