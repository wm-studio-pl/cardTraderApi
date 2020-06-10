<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Card as CardResource;
use App\Card;
use App\Offer;
use Illuminate\Http\Request;
use App\Http\Resources\Offer as OfferResource;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::paginate(20);
        return OfferResource::collection($offers);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::with('user', 'card_offered', 'card_wanted')->where('is_active', '=', '1')->findOrFail($id);
        return new OfferResource($offer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $offeredCardId = $request->card_offered;
        $wantedCardId = $request->card_wanted;
        $userCards = $user->cards()->where('card_id','=',$offeredCardId)->get();
        $countOfferedCards = 0;
        if (isset($userCards[0])) $countOfferedCards = $userCards[0]->pivot->qty;
        if ($countOfferedCards < 1) {
            return response()->json(['error'=>['user'=>'Użytkownik nie ma takiej karty']], 404);
        }
        $wantedCard = Card::find($wantedCardId);
        if (empty($wantedCard)) {
            return response()->json(['error'=>['user'=>'Brak karty chcianej w katalogu']], 404);
        }
        $offer = $request->isMethod('put')
            ? Offer::findOrFail($request->offer_id)
            : new Offer();
        $offer->user_id = $user->id;
        $offer->card_offered = $offeredCardId;
        $offer->card_wanted = $wantedCardId;
        if ($request->isMethod('post')) {
            $offer->is_active = 1;
            $offer->status = 'active';
        }
        if (!empty($request->input('active',''))) $offer->is_active = $request->active;
        if (!empty($request->input('status',''))) $offer->status = $request->status;
        if ($offer->save()) {
            return new OfferResource($offer);
        }
    }

    public function find(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //TODO isUserOwnedOffer
        $user = Auth::user();
        $offer = Offer::findOrFail($id);
        if(!empty($offer)) {
            if ($offer->user_id != $user->id)
                return response()->json(['error'=>['user'=>'Nie posiadasz tej oferty!']], 404);
            $offer->is_active = 0;
            $offer->status = 'deleted';
            if ($offer->save()) {
                return new OfferResource($offer);
            }
        } else
            return response()->json(['error'=>['user'=>'Oferta o podanym Id nie istenieje']], 404);
        return response()->json(['erro'=>['error'=>'Nieznany błąd podczas usuwania oferty']], 404);
    }
}
