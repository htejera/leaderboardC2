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
 * A self hosted Open source PHP Leaderboard. Post, fetch and display high scores.
 * @link http://ukelelestudio.com
 */

//Permissive mode
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin');

$f3 = require('lib/base.php');
$f3->config('config.ini');
$f3->set('AUTOLOAD', 'app/;app/inc/');
$f3->set('DEBUG',3);

$leaderboard=Leaderboard::instance();
$leaderboardC2=LeaderboardC2::instance();
$construct2=Json2Construct2::instance();
$crypt=Cryptme::instance();
$util=Utils::instance();

$f3->set('leaderboard',$leaderboard);
$f3->set('leaderboardC2',$leaderboard);
$f3->set('construct2',$construct2);
$f3->set('cryptme',$crypt);
$f3->set('util',$util);

$sanidad=Sanidad::instance($f3);
$f3->set('sanidad',$sanidad);

//Routing
$f3->route('GET /addscore/@game/@key/@playername/@score','leaderboardC2->addScore');
$f3->route('GET /getlastrank/@game/@playername','leaderboardC2->getLastRank');
$f3->route('GET /getbestrank/@game/@playername','leaderboardC2->getBestRank');
$f3->route('GET /topscores/@game/@limit','leaderboardC2->topScores');
$f3->route('GET /topscores/@game','leaderboardC2->topScores');

$f3->set('ONERROR',function($f3){echo $f3->get('ERROR.text');});
$f3->run();
