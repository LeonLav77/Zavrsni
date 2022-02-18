<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function card($data){
        $pieces = explode("&dynamicId=", $data);
        $staticID = $pieces[0];
        $dynamicID = $pieces[1];
        $card = Card::where('staticID', $staticID)->first();
        if(!$card){
            return response()->json([
                'message' => 'Card not found'
            ], 404);
        }
        if($card->dynamicID == $dynamicID){
            $newDynamicID = rand(1000000000000000,9999999999999999);
            $card->dynamicID = $newDynamicID;
            $card->balance = $card->balance - 7;
            $card->save();

            return response()->json([
                'newBalance' => $card->balance,
                'newDynamicID' => $newDynamicID
            ], 200);
        }
        return response()->json([
            'card' => $card->id,
        ], 200);
    }

    public function createCard(){
        $newStaticID = rand(1000000000000000,9999999999999999);
        $newDynamicID = rand(1000000000000000,9999999999999999);
        $newBalance = 200;
        $card = new Card;
        $card->staticID = $newStaticID;
        $card->dynamicID = $newDynamicID;
        $card->balance = $newBalance;
        $card->save();
        return response()->json([
            'card_id' => $newStaticID,
            'card_dynamicId' => $newDynamicID,
            'card_balance' => $newBalance,
        ], 201);
    }
}
