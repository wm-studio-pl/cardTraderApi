<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Resources\Card as CardResource;
use App\Http\Resources\UserSimple;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Card;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserSimple as UserSimpleResource;

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

    public function list() {
        $users = User::all()->keyBy->id;
        return UserSimpleResource::collection($users);
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
    }

    public function info($id)
    {
        $user = User::findOrFail($id);
        if (empty($user)) return response()->json(['error'=>['user'=>'User with this id not exists']], 404);
        $user->email='';
//        $user->pass
        return new UserResource($user);
    }

    public function addCard(Request $request, $id)
    {
        $user = Auth::user();
        if (empty($user))
        {
            return response()->json(['error'=>['email'=>'User with this email not exists']], 404);
        }
        $card = Card::findOrFail($id);
        if (empty($card))
            return response()->json(['error'=>['card_id'=>'Card with this id not exists']], 404);
        $cardOwned = $user->cards->firstWhere('id','=',$id);
        $cardsCount = $cardOwned->pivot->qty??0;
        if (!empty($cardOwned)) {
            $user->cards()->detach($id);
        }
        $user->cards()->attach($id, ['qty'=>$cardsCount+1]);
        $user->push();
        $user->refresh();
        return new UserResource($user);
    }

    public function subCard(Request $request, $id)
    {
        $user = Auth::user();
        if (empty($user))
        {
            return response()->json(['error'=>['email'=>'User with this email not exists']], 404);
        }
        $card = Card::findOrFail($id);
        if (empty($card))
            return response()->json(['error'=>['card_id'=>'Card with that id not exists']], 404);
        $cardOwned = $user->cards->firstWhere('id','=',$id);
        $cardsCount = $cardOwned->pivot->qty??0;
        if ($cardsCount == 0) {
            return response()->json(['error'=>['card_id'=>'User not have any quantity of this card']], 404);
        }
        $cardsCount--;
        $user->cards()->detach($id);
        if ($cardsCount)
            $user->cards()->attach($id, ['qty'=>$cardsCount]);
        $user->push();
        $user->refresh();
        return new UserResource($user);
    }

    public function test(Request $request)
    {
        return response()->json([self::TEXT_DATA_CONTAINER=>'method: '.$request->method().', params: '.print_r($request->input(), 1) . ' i nagłówek Authorization: ' . $request->header("Authorization")]);
    }


}
