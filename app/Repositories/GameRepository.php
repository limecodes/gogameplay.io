<?php

namespace App\Repositories;

use App\Contracts\GameInterface;
use App\Models\Game;

class GameRepository implements GameInterface {
    public function getGames()
    {
        return Game::all();
    }
}
