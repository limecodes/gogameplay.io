<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\GameInterface;

class IndexController extends Controller
{
    protected $gameRepository;

    public function __construct(GameInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function index()
    {
        $games = $this->gameRepository->getGames();

        return view('index', [
            'games' => $games
        ]);
    }
}
