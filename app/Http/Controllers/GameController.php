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

        return view('game', [
            'title' => $game->title,
            'image' => $game->image,
            'price' => $game->price,
            'device' => $device
        ]);
    }
}
