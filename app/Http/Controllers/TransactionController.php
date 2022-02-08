<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function card($staticID){
        $card = Card::where('staticID', $staticID)->first();
        if(!$card){
            return response()->json([
                'message' => 'Card not found'
            ], 404);
        }
        return response()->json([
            'card' => "Card #{$card->id}",
        ], 200);
    }
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
        $newDynamicID = Str::random(16);
        $card->dynamicID = $newDynamicID;
        $card->save();
        return response()->json([
            'message' => 'Payment successful',
            'newDynamicID' => $newDynamicID
        ], 200);
    }
}
