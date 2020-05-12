<?php

namespace App\Repositories;

use App\Contracts\GamesInterface;
use App\Models\Game;

class GamesRepository implements GamesInterface {
    public function getGames()
    {
        return Game::all();
    }
}
