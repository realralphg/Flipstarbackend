<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Game;
use App\GameUsers;
use App\UserWallet;
use Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // step one - get all games
        $games = Game::get();

        // step two - return games with creator and players information
        return response()->json([
            'status' => 'success',
            'message' => 'all games',
            'data' => $games->load('user','players','players.user'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // step one deducts the game amount from the current users wallet
        UserWallet::where('user_id', Auth::user()->id)->decrement('amount', $request->get('category'));

        // step two - created the game
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $input['amount'] = $request->get('category');
        $game = Game::create($input);

         // step three- create or add the current player to the game play
        GameUsers::create([
            'user_id' => Auth::user()->id,
            'game_id' => $game->id,
            'star' => $request->get('ratingModel')
        ]);

        // return the game created
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $game->load('user'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
