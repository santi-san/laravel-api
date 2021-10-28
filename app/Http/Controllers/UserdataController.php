<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userdata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



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
    public function addUsers(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'age' => 'required',
            'about' => 'required',
            'genre' => 'required'


        ]);
        // Si falla la validacion devuelve json con status 422
        if($validator->fails()) {
            return $this->sendError($validator->errors(),"Error en la validacion de datos",422); 
        }
        // Si pasa la validacion, guardar datos recibidos
        $input = $request->all();
        //guarda la contraseña encriptada con bcrypt
        $input['password'] = bcrypt($request->get("password"));
        //crear usuario
        $new_user = User::create($input); 
        $token = $new_user->createToken('myApp')->accessToken;

        $userData = new Userdata();
        $userData->iduser = $new_user->id;
        $userData->name = $request->get('name');
        $userData->photo = $request->get('photo');
        $userData->age = $request->get('age');
        $userData->genre = $request->get('genre');
        $userData->about = $request->get('about');
        $userData->save();
        
        $data = [
            'token' => $token,
            'user' => $new_user,
            'user_data' => $userData,
        ];
        return $this->sendResponse($data, 'Registered user successfully');
    }

    public function updateUsers(Request $request) {
        $user = User::find($request->get('id'));

        if($user === null){
            return $this->sendError("Error en los datos", ['el usuario no existe'] , 422); 
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'required',
            'about' => 'required',
            'genre' => 'required'
        ]);
        // Si falla la validacion devuelve json con status 422
        if($validator->fails()) {
            return $this->sendError($validator->errors(),"Error en la validacion de datos",422); 
        }
        // update table USERS
        $user->name = $request->get('name');
        $user->save();

        // updata table USERDATAS
        $userData = Userdata::where('iduser', $request->get('id'))->first();
        
        $userData->name = $request->get('name');
        $userData->photo = $request->get('photo');
        $userData->age = $request->get('age');
        $userData->genre = $request->get('genre');
        $userData->about = $request->get('about');
        $userData->save();

        $data = [
            'user' => $user,
            'user_data' => $userData,
        ];
        return $this->sendResponse($data, 'User updated successfully');
    }

    public function deleteUsers(Request $request) {
        $user = User::find($request->get('id'));

        if($user === null){
            return $this->sendError("Error en los datos", ['el usuario no existe'] , 422); 
        }

        $user->delete();
        $userData = Userdata::where('iduser', $request->get('id'))->first();
        $userData->delete();

        return $this->sendResponse([
            'status' => 'ok'
        ], 'User deleted successfully' );

    }
}
