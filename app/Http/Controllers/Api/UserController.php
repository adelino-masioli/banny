<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;

class UserController extends Controller
{

    use RegistersUsers;
    //login
    protected function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name'       => 'required|string|max:255',
                'email'      => 'required|string|email|unique:users',
                //'password'   => 'required|string|min:6|confirmed',
                'password'   => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return $validator->errors();
            }

            $user = User::create([
                'type'       => 'USER',
                'name'       => $request['name'],
                'email'      => $request['email'],
                'password'   => bcrypt($request['password']),
                'status'     => 1
            ]);
            return ['status'=>true];
        }catch(\Exception $e){
            return ['status'=>false];
        }
    }
    //login
    protected function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $user = auth()->user();
            $user->token = $user->createToken('email')->accessToken;
            return $user;
        }else{
            return ['status'=>false];
        }
    }
    public function show(Request $request)
    {
        $user =  $request->user();
        $user->token = $user->createToken('email')->accessToken;
        return $user;
    }

    public static function update(Request $request)
    {
        $user = $request->user();
        try{
            if($request['password']){
                $validator = Validator::make($request->all(), [
                    'name'       => 'required|string|max:255',
                    'email'      => 'required|string|email|unique:users,email, '.$request->id,
                    'password'   => 'required|string|min:6|confirmed',
                ]);
                if ($validator->fails()) {
                    return $validator->errors();
                }
                $data = [
                    'type'       => $request['type'],
                    'cellphone'  => $request['cellphone'],
                    'name'       => $request['name'],
                    'email'      => $request['email'],
                    'password'   => bcrypt($request['password']),
                    'status'     => $request['status']
                ];

                $user->update($data);
                $user->token = $user->createToken('email')->accessToken;
                return $user;
            }else{
                $validator = Validator::make($request->all(), [
                    'name'      => 'required|string|max:255',
                    'email'     => 'required|string|email|unique:users,email, '.$request->id,
                ]);
                if ($validator->fails()) {
                    return $validator->errors();
                }
                $data = [
                    'type'       => $request['type'],
                    'cellphone'  => $request['cellphone'],
                    'name'       => $request['name'],
                    'email'      => $request['email'],  
                    'status'     => $request['status']
                ];

                $user->update($data);
                $user->token = $user->createToken('email')->accessToken;
                return $user;
            }
        }catch(\Exception $e){
            return ['status'=>false];
        }
    }
    public function getAll(Request $request){
        $users = User::select('id', 'type', 'cellphone', 'email', 'name', 'status')->where('status', '1');

        if(trim($request->name) !=  ''){
            $users = $users->where('name', 'like', '%'.trim($request->name).'%');
        }
        if(trim($request->email)){
            $users = $users->where('email', trim($request->email));
        }

        if($users->count() > 0){
            return ['status' => true, 'users'=> $users->get()];

        }else{
            return ['status' => false];
        }
    }

    public static function destroy(Request $request)
    {
        try{
            $user = User::findOrFail($request->id);
            $status['status'] = 2;
            $user->update($status);
            return ['status'=>true];

        }catch(\Exception $e){
            return ['status'=>true];
        }
    }
}
