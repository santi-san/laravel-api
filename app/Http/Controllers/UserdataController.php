<?php

namespace App\Http\Controllers;

use App\Models\Userdata;
use Illuminate\Http\Request;

class UserdataController extends Controller
{
    public function getUsers() {
        $data = [];
        $users = Userdata::all();
        $data['users'] = $users;

        return response()->json([
            'data' => $data
        ]);
    }
}
