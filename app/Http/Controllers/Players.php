<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use View;


class Players extends Controller
{
    private function getPlayers(){
        return DB::table('users')->where('user_type', 'player')->orderBy('can_play_goalie', 'desc')->get();
    }

    protected function showPlayers($players){

        $count = DB::table('users')->where('user_type', 'player')->count();

        $columns = 2;
        while( !((22 >= $count/$columns and $count/$columns >= 18) and ($columns > $count % $columns)) ){
            $columns += 2;
        }
        $teams = [];

        while($columns > 0){
            $teams[$columns] = [$players->shift()];
            $columns -= 1;
        }

        $sorted = $players->sortByDesc('ranking');

        function rank($obj){
            $sum = 0;
            foreach($obj as $elem){
                $sum += $elem->ranking;
            }
            return $sum;
        }

        foreach($sorted as $player){
            //sum rankings
            //sort by rankings
            //push $player to $teams[3]

            usort($teams, function($a, $b) { return rank($a) < rank($b); });
            array_push($teams[3], $player);
        }

        

        $collection = collect($players)->groupBy('user_type')->toArray();
        
        return $teams;
    }

    public function renderPlayers(){
        return View::make("players")->with(array('collection'=>self::showPlayers(self::getPlayers())));
    }
    
}
