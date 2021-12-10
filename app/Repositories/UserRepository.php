<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface{

    use ResponseAPI;
    public function getAllUsers()
    {
        try{
            $user = User::all();
            return $this->success("All users list",$user);
        }catch(Exception $e){
            return $this->error($e->getMessage(),500);
        }
    }

    public function getUserById($id)
    {
        try{
            $user = User::find($id);
            return $this->success("Find user by id",$user);
        }catch(Exception $e){
            return $this->error($e->getMessage(),500);
        }
    }

    public function requestUser(UserRequest $request,$id=null)
    {
        DB::beginTransaction();
        try{
            $user=$id?User::find($id):new User;
            if($id&&!$user) return $this->error("No user with id=$id",404);
            $user->name=$request->name;
            $user->email=preg_replace('/\s+/','',strtolower($request->email));
            if(!$id) $user->password=Hash::make($request->password);
            $user->save();
            DB::commit();
            return $this->success($id?"User Updated Successfully":"User created successfully",$user);
        }catch(Exception $e){
            DB::rollBack();
            return $this->error("An error occured please try again later",500);
        }
    }

    public function deleteUserById($id)
    {
        DB::beginTransaction();
        try{
            $user=User::find($id);
            if(!$user) return $this->error("No user with id=$id",404);
            $user->delete();
            DB::commit();
            return $this->success("User deleted successfully",$user,200);
        }catch(Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage(),500);
        }
    }
}