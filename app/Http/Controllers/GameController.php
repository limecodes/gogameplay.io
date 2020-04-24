<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index($name, Request $request)
    {
        // Visitor is already created when the page is rendered, so I need to pass the uuid to the React application
        // On Android, if the user changes their IP, then I need to update the visitor record with the UUID
        // The uuid is persisted in the local storage.
        // I can also have a flash for Android if the user is on wifi
        // For iPhone, I don't have access to the connection type so I can only tell by IP
        // I need to make sure that I don't call the IP2Location API more than I need to
        // User then chooses their carrier and proceeds to offer selection

        // TODO: Get the user's geo location by their IP address
        // On Android (network.connection)
        //  -> Check if the user if on wifi
        //      -> If the user is on wifi, ask the user to switch to cellular connection
        //      -> Onchange of network connection, update the IP and lookup the carrier
        // On iPhone
        //  -> Check the user's IP and check if mobile carrier values are there
        //  -> If they are not present, then ask the user to switch to cellular network
        //  -> Have them select their carrier

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
