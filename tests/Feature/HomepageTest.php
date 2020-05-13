<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;

class HomepageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     *
     * @test
     */
    public function homepageShouldBeOK()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertViewIs('index');
    }

    /**
     *
     * @test
     */
    public function homepageShouldBeUsingStaticAssetsEndpoint()
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('<link href="'.env('APP_STATIC_ASSETS_ENDPOINT').'/css/app.css" rel="stylesheet">', false);
    }

    /**
     *
     * @test
     */
    public function shouldDisplayGameFromDatabase()
    {
        $game = factory(Game::class)->create([
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomNumber(2)
        ]);

        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertViewHas('<img src="'.$game->image.'" />', false)
            ->assertViewHas('<span class="badge badge-primary">$'.$game->price.'</span>', false)
            ->assertViewHas('<a href="/game/'.$game->slug.'" class="btn btn-success tyl">', false)
            ->assertViewHas('<span class="strike">$'.$game->price.'</span> => $0.00</span>', false);
    }

    /**
     *
     * @test
     */
    public function shouldDisplayMultipleGamesFromDatabase()
    {
        $game = factory(Game::class, 3)->create([
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomNumber(2)
        ]);

        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertSeeTextInOrder([
                $game[2]->price,
                $game[1]->price,
                $game[0]->price
            ])
            ->assertViewHas('<img src="'.$game[2]->image.'" />', false)
            ->assertViewHas('<img src="'.$game[1]->image.'" />', false)
            ->assertViewHas('<img src="'.$game[0]->image.'" />', false)
            ->assertViewHas('<a href="/game/'.$game[2]->slug.'" class="btn btn-success tyl">', false)
            ->assertViewHas('<a href="/game/'.$game[1]->slug.'" class="btn btn-success tyl">', false)
            ->assertViewHas('<a href="/game/'.$game[0]->slug.'" class="btn btn-success tyl">', false);
    }

    /**
     *
     *
     * @test
     */
    public function shouldNotShowMoreThanFive()
    {
        // $game = factory(Game::class, 6)->create([
        //     'image' => $this->faker->imageUrl(),
        //     'price' => $this->faker->randomNumber(2)
        // ]);

        // $response = $this->get('/');

        // $response
        //     ->assertStatus(200)
        //     ->assertDontSee('<img src="'.$game[5]->image.'" />', false);
    }
}
