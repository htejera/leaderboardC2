<?php
/**
* Some integration test for the leaderboard API.
*/
$service = new Web;

$baseUrl = "http://localhost/leaderboard";
$key = "m6WbwJ2WnYmMv5Wa";
$gameId = "test"; 
$player = "evariste";
$score = 1831;
$bestScore = 5025;
$bestRank = 0; //The rank is zero-based
$lastRank = 1;
$topScoresLimit = 1;

$topScoresExpected = array(
    0 => array(
            0 => array(
                    0 => '1',
                    ),
                    1 => array(
                                0 => 'evariste',
                            ) ,
                    2 => array(
                                0 => '5025',
                            ) ,
                    3 => array(
                                0 => '2015-01-14 01:01:11',
                            ) ,
                ) ,
    1 => array(
            0 => array(
                    0 => '2',
                    ) ,
                    1 => array(
                                0 => 'pierre',
                            ),
                    2 => array(
                                0 => '108',
                            ),
                    3 => array(
                                0 => '2015-01-14 01:01:30',
                                ), 
                ),
);

// Action: Top Scores
$topScoresResult = json_decode($service->request($baseUrl.'/topscores/'.$gameId)["body"]);

$test->expect(
    $topScoresResult != null,
    sprintf($msg,'topScores','Return value should be a valid JSON')
);

$topScoresArray = $topScoresResult->{'data'};

$test->expect(
    (array_values($topScoresArray) == array_values($topScoresExpected)) == 1,
    sprintf($msg,'topScores','Return values should be equal to expected')
);

$topScoresWithLimit = json_decode($service->request($baseUrl.'/topscores/'.$gameId.'/'.$topScoresLimit)["body"])->{"data"};

$test->expect(
    count($topScoresWithLimit) == 1,
    sprintf($msg,'topScores','Return values should be 1')
);

// Action: Add Score
$addScoreResult = json_decode($service->request($baseUrl.'/addscore/'.$gameId."/".$key."/".$player."/".$score)["body"]);

$test->expect(
    $addScoreResult != null,
    sprintf($msg,'AddScore','Return value should be a valid JSON')
);

$test->expect(
    (strcmp($addScoreResult,'{"result":1}') == 0),
    sprintf($msg,'AddScore','Return value should be 1')
);

// Action: Get Best Rank
$bestRankResult = json_decode($service->request($baseUrl.'/getbestrank/'.$gameId."/".$player)["body"]);

$test->expect(
    $bestRankResult != null,
    sprintf($msg,'GetLastRank','Return value should be a valid JSON')
);

$test->expect(
    $bestRankResult->data->player_name == "evariste",
    sprintf($msg,'GetBestRank','Return value should be evariste')
);

$test->expect(
    $bestRankResult->data->score == $bestScore,
    sprintf($msg,'GetBestRank','Return value should be '.$bestScore)
);

$test->expect(
    $bestRankResult->data->bestrank == $bestRank,
    sprintf($msg,'GetBestRank','Return value should be '.
$bestRank)
);

// Action: Get Last Rank
$lastRankResult = json_decode($service->request($baseUrl.'/getlastrank/'.$gameId."/".$player)["body"]);

$test->expect(
    $lastRankResult != null,
    sprintf($msg,'GetLastRank','Return value should be a valid JSON')
);

$test->expect(
    $lastRankResult->data->player_name == "evariste",
    sprintf($msg,'GetLastRank','Return value should be evaroste')
);

$test->expect(
    $lastRankResult->data->score == 1831,
    sprintf($msg,'GetLastRank','Return value should be 1831')
);

$test->expect(
    $lastRankResult->data->lastrank == $lastRank,
    sprintf($msg,'GetLastRank','Return value should be '.
$lastRank)
);