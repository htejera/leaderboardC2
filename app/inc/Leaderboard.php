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
 * The leaderboard.
 */
class Leaderboard extends \Prefab {
    
    /**
     * Score insert.
     */
    private $addScoreInsert = 'INSERT INTO score 
                               VALUES(null,"%s","%s",
                               CURRENT_TIMESTAMP);';
   
    /**
     * Last rank query.
     */
    private $lastRankQuery = 'SELECT p1.*,
                                (SELECT count(*) 
                                FROM "score" as p2 
                                WHERE p2.score > p1.score)
                              AS lastrank 
                              FROM "score" as p1 
                              WHERE p1.player_name = "%s" 
                              ORDER BY sqltime DESC
                              LIMIT  1;';    

    /**
     * Best rank query.
     */    
    private $bestRankQuery = 'SELECT p1.*,
                                (SELECT count(*) 
                                FROM "score" as p2 
                                WHERE p2.score > p1.score)
                              AS bestrank 
                              FROM "score" as p1 
                              WHERE p1.player_name = "%s" 
                              ORDER BY bestrank ASC
                              LIMIT  1;';      

    /**
     * Top scores query.
     */        
    private $topScoresQuery = 'SELECT * 
                               FROM "score" 
                               WHERE 1 
                               ORDER BY score DESC 
                               LIMIT %s';
    
    /**
     * Add score given the score and the username.
     * 
     * @param object $db The database instance.                        
     * @param object $f3 A f3 instance.
     * @param string $key The key.
     * @param string $playername The playername.   
     * @param string $score The player's score.
     * @return string Success returns 1 otherwise returns 0. (JSON representation)
     */
    function addScore($db, $f3, $key, $playername, $score){   
        $score = $f3->get('sanidad')->auditScore($score);          
        $insert = sprintf($this->addScoreInsert, $playername, $score);
        
        if($this->validateKey($db, $f3, $key)){                 
            $db->exec($insert);    
            return json_encode('{"result":1}');
        }else {
            return json_encode('{"result":0}');
        }    
    }

    /**
     * Get the last rank given the playername.
     *
     * @param object $db The database instance.                        
     * @param object $f3 A f3 instance.
     * @param string $playername The playername.  
     * @return string Returns the JSON representation of a last rank for 
     * the given playername.
     */
    function getLastRank($db, $f3, $playername){        
        $query = sprintf($this->lastRankQuery, $playername);        
        return json_encode($db->exec($query));
    }

    /**
     * Get the best rank given the username. 
     * The ranks is zero-based.
     *
     * @param object $db The database instance.                        
     * @param object $f3 A f3 instance.
     * @param string $playername The playername.  
     * @return string Returns the JSON representation of a best rank for 
     * the given playername.
     */
    function getBestRank($db, $f3, $playername){
       $query = sprintf($this->bestRankQuery, $playername);    
       return json_encode($db->exec($query));
    }

    /**
     * Get the top scores.
     *
     * @param object $db The database instance.                        
     * @param object $f3 A f3 instance.
     * @return array Returns an array of top scores.
     */
    function topScores($db, $f3){    
        $limit = "10";
        $paramsLimit = $f3->get('PARAMS.limit');
        
        if(!is_null($paramsLimit)) { 
            if(is_numeric($paramsLimit)){
                $compare = intval($paramsLimit);                          
                if($compare > 0 || $compare < 30) {
                    $limit = $paramsLimit;
                }  
            }               
        }         
    
        $query = sprintf($this->topScoresQuery,$limit); 
        return $db->exec($query);   
    }
    
    /**
     * Validate the key.
     *
     * @param object $db The database instance.                        
     * @param object $f3 A f3 instance.
     * @param string $key The key
     * @return boolean Success returns true, otherwise returns false.
     */
    function validateKey($db, $f3, $key){                    
        $table = "keys";
        $queryExist = 'SELECT count(*) AS exist 
                       FROM "'.$table.'" 
                       WHERE key="'.$key.'";';
        
        $queryTotal = 'SELECT count(*) AS total 
                       FROM "'.$table.'" 
                       WHERE 1;';
        
        if($f3->get('sanidad')->antiCheat($key)){        
            $exist = (int)$db->exec($queryExist)[0]["exist"];

            if($exist == 0){
                $total = (int)$db->exec($queryTotal)[0]["total"];
                //Reset
                if($total >= 100){
                    $db->exec('DELETE FROM "'.$table.'";'); 
                    $db->exec('DELETE FROM "sqlite_sequence" 
                               WHERE name = "'.$table.'";');             
                }
                
                $db->exec('INSERT INTO "'.$table.'" ("id","key") 
                           VALUES (NULL,"'.$key.'")');                  
                return true;
            }                        
        }
     return false;           
   }
}

    
   