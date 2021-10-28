<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    //Register controller
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function testOauth()
    {
        $user = Auth::user();
        return $this->sendResponse($user, 'successfully recovered users');
    }

    public function test()
    {
        return $this->sendResponse([
            'status' => 'ok'
        ], 'successfully recovered users' );

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',

        ]);
        // Si falla la validacion devuelve json con status 422
        if($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 422 );
        }
        // Si pasa la validacion, guardar datos recibidos
        $input = $request->all();
        //guarda la contraseña encriptada con bcrypt
        $input['password'] = bcrypt($request->get("password"));
        //crear usuario
        $new_user = User::create($input); 
        $token = $new_user->createToken("MyApp")->accessToken;

        $data = [
            'token' => $token,
            'new_user' => $new_user
        ];
        return $this->sendResponse($data, 'Welcome');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
