<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    use ResponseAPI;
    protected $userInterface;
    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface=$userInterface;
    }
    public function login(Request $request){
        if(!Auth::attempt($request->only("email","password"))){
            return $this->error("Credentials failed",401);
        }
        $user=Auth::user();
        return $this->success("Successfully logged in",["token"=>$user->createToken("Login")->plainTextToken]);
    }

    public function register(UserRequest $request){

      return $this->userInterface->requestUser($request);
      
    }
}
