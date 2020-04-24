<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index($name, Request $request)
    {
    	// To make the controller return 422
    	// Later, will show a page with an error
    	$validator = Validator::make(['name' => $name], [
    		'name' => 'required|string|alpha|max:50'
    	]);

    	if ($validator->fails()) {
    		return response($validator->errors(), 422);
    	}

    	// TODO: This should actually be done through a service //
    	$game = Game::where('name', $name);

    	if ($game->count() > 0) {
    		// return view('welcome');
            return response("The name of the game is $name", 200);
    	} else {
    		return response('game does not exist', 404);
    	}
    }
}
