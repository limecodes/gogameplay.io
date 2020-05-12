<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\GamesInterface;

class IndexController extends Controller
{
    protected $gamesRepository;

    public function __construct(GamesInterface $gamesRepository)
    {
        $this->gamesRepository = $gamesRepository;
    }

    public function index()
    {
        $games = $this->gamesRepository->getGames();

        return view('index', [
            'games' => $games
        ]);
    }
}
