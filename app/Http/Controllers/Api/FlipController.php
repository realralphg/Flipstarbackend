<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\UserWallet;
use App\GameUsers;
use App\Game;
use App\User;
use Auth;

class FlipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * for joining a game or existing flip ####
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // step one deducts the game amount from the current users wallet
        UserWallet::where('user_id', Auth::user()->id)->decrement('amount', $request->get('category'));

        // step two create or add the current player to the game play
        $game = GameUsers::create([
            'user_id' => Auth::user()->id,
            'game_id' => $request->get('game_id'),
            'star' => $request->get('ratingModel'),
        ]);

        // step three get all users that played this game
        $users =  GameUsers::where('game_id',$request->get('game_id'))->get();

        // step foure - creates variable call is_complete that check if the current players are complet
        $is_complet = false;

        // step five simply pass the object of the gameuser created to the data variable
        $data = $game;

        //step six check if the current players is >= 3
        // if true update the current game to complted
        // then do a random selection of the winner
        if (count($users) >= 3) {
            Game::where('id', $request->get('game_id'))->update([
                'is_completed' => true
            ]);

            // step seven randomly select a winner from all players that played this game
            // step eight - get the object of the user that was selected from the random selection above
            //  then turn the is_completed to true
            // and pass the winner object to the data vareable
            $winner_id =  GameUsers::where('game_id',$request->get('game_id'))->inRandomOrder()->limit(1)->first('user_id');
            $winner = User::where('id', $winner_id->user_id)->first();
            $is_complet = true;
            $data = $winner;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'is_complet' => $is_complet,
            'data' => $data,
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
