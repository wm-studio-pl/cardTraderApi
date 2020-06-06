<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    public $successStatus = 200;
    public const TEXT_DATA_CONTAINER = 'data';

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('cardTrader')->accessToken;
            return response()->json([self::TEXT_DATA_CONTAINER=>$success], $this->successStatus);
        }
        else
            {
                return response()->json(['error'=>'Unauthorised'], 401);
            }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $testUser = User::where('email', '=', $input['email'])->first();
        if (!empty($testUser)) {
            return response()->json(['error'=>['email'=>'User with this email already exists']], 409);
        }
        $user = User::create($input);
        $success['token'] = $user->createToken('cardTrader')->accessToken;
        $success['name'] = $user->name;
        return response()->json([self::TEXT_DATA_CONTAINER=>$success], $this->successStatus);
    }

    public function details()
    {
        $user = Auth::user();
        return new UserResource($user);
        //return response()->json([self::TEXT_DATA_CONTAINER=>$user], $this->successStatus);
    }

    public function test(Request $request)
    {
        return response()->json([self::TEXT_DATA_CONTAINER=>'method: '.$request->method().', params: '.print_r($request->input(), 1) . ' i nagłówek Authorization: ' . $request->header("Authorization")]);
    }

}
