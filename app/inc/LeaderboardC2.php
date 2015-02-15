<?php
/**
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 henry.tejera
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * The leaderboard wrapper for Construct2.
 */
class LeaderboardC2 extends \Prefab {
    
    /**
     * DB instance.
     */
    private $db;
    
    /**
     * The player's name.
     */
    private $playername;
    
    /**
     * Creates a new DB instance and clean some data.
     * 
     * @param object $f3 A f3 instance.
     */
    function beforeRoute($f3) {        
         $base = 'data/'.$f3->get('PARAMS.game').'.sqlite';
         $this->playername = $f3->get('sanidad')
                            ->sanitize($f3->get('PARAMS.playername')); 
        
         if(file_exists($base)){
             $this->db = new DB\SQL('sqlite:'.$base);       
         }else{
             $f3->error(500);         
         }
    }
    
    
    /**
     * Add Score given the score and the username.
     *
     * @param object $f3 A f3 instance.     
     * @return Success output 1 otherwise output 0 (JSON representation).
     */
    function addScore($f3){
        echo $f3->get('leaderboard')
                ->addScore($this->db,
                           $f3,
                           $f3->get('PARAMS.key'),
                           $this->playername,
                           $f3->get('PARAMS.score'));
    }   
        
    /**
     * Get the last rank given the username.
     *
     * @param object $f3 A f3 instance.  
     * @return Output a Construct2 dictionary (JSON representation).
     */
    function getLastRank($f3){
        $lastrank = $f3->get('leaderboard')
                        ->getLastRank($this->db, 
                                      $f3,
                                      $this->playername);
        
        $lastrank = $f3->get('util')
                       ->byeArray($lastrank);
        
        echo $f3->get('construct2')->toC2Dictionary($lastrank);
    }      

    /**
     * Get the best rank given the username. 
     * The ranks is zero-based.
     * 
     * @param object $f3 A f3 instance.  
     * @return Output a Construct2 dictionary (JSON representation).
     */
    function getBestRank($f3){
        $bestrank = $f3->get('leaderboard')
                       ->getBestRank($this->db, 
                                     $f3,
                                     $this->playername);
        
        $bestrank = $f3->get('util')
                       ->byeArray($bestrank);
        
        echo $f3->get('construct2')->toC2Dictionary($bestrank);
    }
            
    /**
     * Get the top scores.
     *
     * @param object $f3 A f3 instance.  
     * @return Output a Construct2 Array (JSON representation).
     */
    function topScores($f3){
        $topscore = $f3->get('leaderboard')
                       ->topScores($this->db, $f3);
        $scores = '';

        foreach ($topscore as $key => $value) {
            $scores = $scores.'[';
            foreach ($value as $pato => $ruzu) {
                $scores = $scores.'["'.trim($ruzu).'"],';       
            }
            $scores = $scores.'],';
        }    

        $c2Array = $f3->get('construct2')
                      ->toC2Array(count($topscore),"4","1", 
                                  str_replace(',]', ']',$scores));
        
        echo str_replace(']],]]}', ']]]}',$c2Array);
    } 
}