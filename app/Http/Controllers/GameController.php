<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\GameRequest;

class GameController extends Controller
{
    public function index($name, Request $request)
    {
    	$validator = Validator::make(['name' => $name], [
    		'name' => 'required|string|alpha|max:50'
    	]);

    	if ($validator->fails()) {
    		return response($validator->errors(), 422);
    	}

    	$game = Game::where('name', $name);

    	if ($game->count() > 0) {
            $device = $request->headers->get('device');

            $gameObject = $game->first();
            $gameTitle = $gameObject->title;
            $gameImage = $gameObject->image;
            $gamePrice = $gameObject->price;

    		return view('game', ['title' => $gameTitle, 'image' => $gameImage, 'price' => $gamePrice, 'device' => $device]);
    	} else {
    		return response('game does not exist', 404);
    	}
    }
}
