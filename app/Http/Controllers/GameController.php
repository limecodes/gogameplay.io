<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index(Game $game, Request $request)
    {
        $device = $request->headers->get('device');

        $gameTitle = $game->title;
        $gameImage = $game->image;
        $gamePrice = $game->price;

        return view('game', ['title' => $gameTitle, 'image' => $gameImage, 'price' => $gamePrice, 'device' => $device]);
    }
}
