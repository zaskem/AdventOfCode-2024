<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $prizeFactor = 10000000000000;
  $runningSum = 0;
  $gameNumber = 0;
  $games = array();
  $pushMatches = array();
  $ACost = 3;
  $BCost = 1;
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      $trimmed = rtrim($buffer);
      if (0 == strlen($trimmed)) {
        $gameNumber++;
      } else {
        $games[$gameNumber][] = $trimmed;
      }
    }
  }
  foreach ($games as $number => $game) {
    $AData = explode(': ', $game[0]);
    preg_match_all('/\d+/', $AData[1], $match, PREG_SET_ORDER);
    $buttonAX = $match[0][0];
    $buttonAY = $match[1][0];
    $BData = explode(': ', $game[1]);
    preg_match_all('/\d+/', $BData[1], $match, PREG_SET_ORDER);
    $buttonBX = $match[0][0];
    $buttonBY = $match[1][0];
    $PrizeData = explode(': ', $game[2]);
    preg_match_all('/\d+/', $PrizeData[1], $match, PREG_SET_ORDER);
    $prizeX = $prizeFactor + $match[0][0];
    $prizeY = $prizeFactor + $match[1][0];
    // Pretty blatantly "stolen" implementation of Cramer's Rule based on this post: https://www.reddit.com/r/adventofcode/comments/1hd7irq/2024_day_13_an_explanation_of_the_mathematics/?rdt=47482
    $AResult = intval(abs((($prizeX * $buttonBY) - ($prizeY * $buttonBX)) / (($buttonAX * $buttonBY) - ($buttonAY * $buttonBX))));
    $BResult = intval(abs((($prizeX * $buttonAY) - ($prizeY * $buttonAX)) / (($buttonAX * $buttonBY) - ($buttonAY * $buttonBX))));
    if (($prizeX == (($AResult * $buttonAX) + ($BResult * $buttonBX))) && ($prizeY == (($AResult * $buttonAY) + ($BResult * $buttonBY)))) {
      $pushMatches[$number][] = array($AResult, $BResult);
    }
  }
  foreach ($pushMatches as $gameNum => $matches) {
    $gameCost = ($matches[0][0] * $ACost) + ($matches[0][1] * $BCost);
    $runningSum += $gameCost;
  }
  print "\nTotal token spend: $runningSum\n";
?>