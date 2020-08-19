<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Players;

class PlayersIntegrityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGoaliePlayersExist () 
    {
/*
		Check there are players that have can_play_goalie set as 1   
*/
		$result = DB::table('users')->where('can_play_goalie', 1)->count();
		$this->assertTrue($result > 1);
	
    }
    public function testAtLeastOneGoaliePlayerPerTeam () 
    {
/*
	    calculate how many teams can be made so that there is an even number of teams and they each have between 18-22 players.
	    Then check that there are at least as many players who can play goalie as there are teams
*/
        $players = DB::table('users')->where('user_type', 'player')->orderBy('can_play_goalie', 'desc')->get();
        $teams = $players->showPlayers($players);

        $goalies = DB::table('users')->where('can_play_goalie', 1)->get();
        
        $this->assertTrue( count($goalies) == count($teams) );
    }
}
