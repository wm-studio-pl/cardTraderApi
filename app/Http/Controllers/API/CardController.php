<?php

namespace App\Http\Controllers\API;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Resources\Card as CardResource;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with(['category', 'subcategory'])->get();
        return CardResource::collection($cards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $card = $request->isMethod('put')
            ? Card::findOrFail($request->card_id)
            : new Card($request->input());
        $card->id = $request->card_id;
        $card->name = $request->name;
        $card->category_id = $request->category_id;
        $card->subcategory_id = $request->subcategory_id;
        $card->short_description = $request->short_description;
        $card->description = $request->description;
        //todo dodać uploading pliku i badanie timestampa
        $card->picture_id = $request->picture_id;
        if ($card->save()) {
            return new CardResource($card);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::findOrFail($id);
        return new CardResource($card);
    }


    public function showInCategory($category_id, $subcategory_id="")
    {
        $filter = [['category_id', '=', $category_id]];
        if ($subcategory_id != "") $filter[] = ['subcategory_id', '=', $subcategory_id];
        $cards = Card::where($filter)->with(['category', 'subcategory'])->get();
        return CardResource::collection($cards);
    }

    public function showUserCards($user_id)
    {
        $cards = Card::with(['category', 'subcategory', 'users'])
                ->whereHas('users', function ($q) use ($user_id){
                    $q->where('user_id', $user_id);
                }
            )->get();
        return CardResource::collection($cards);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        if ($card->delete()) {
            return new CardResource($card);
        }
    }
}
