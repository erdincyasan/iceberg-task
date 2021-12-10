<?php
namespace App\Interfaces;

use App\Http\Requests\UserRequest;

interface UserInterface{

    //return all users
    public function getAllUsers();
    //return user by id
    public function getUserById($id);
    //User create or update
    public function requestUser(UserRequest $request,$id=null);
    //delete user by id
    public function deleteUserById($id);
}
