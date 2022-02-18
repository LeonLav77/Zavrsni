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
        // return response()->json([
        //     'data' => $data,
        //     'staticID' => $staticID,
        //     'dynamicID' => $dynamicID
        // ]);
        $card = Card::where('data', $staticID)->first();
        if(!$card){
            return response()->json([
                'message' => 'Card not found'
            ], 404);
        }
        return response()->json([
            'card' => "Card #{$card->id}",
        ], 200);
    }
    // @d2LP@$dxYlgjgwmr2nK
    public function verifyCard(request $request){
        $validated = $request->validate([
            'staticID' => 'required|exists:cards,staticID|max:255',
            'dynamicID' => 'required|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        if(!$validated){
            return response()->json([
                'message' => 'Invalid data'
            ], 400);
        }
        $card = Card::where('staticID', $request->staticID)->first();
        if(!$card){
            return response()->json([
                'message' => 'Card not found'
            ], 404);
        }
        if($card->dynamicID != $request->dynamicID){
            return response()->json([
                'message' => 'Invalid dynamic ID'
            ], 400);
        }
        $card->balance = $card->balance - $request->price;
        $newDynamicID = rand(1000000000000000,9999999999999999);
        $card->dynamicID = $newDynamicID;
        $card->save();
        return response()->json([
            'message' => 'Payment successful',
            'newDynamicID' => $newDynamicID
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
