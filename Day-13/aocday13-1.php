<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
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
    $prizeX = $match[0][0];
    $prizeY = $match[1][0];
    for ($i=1; $i<=100; $i++) {
      $pushAX = $i * $buttonAX;
      $pushAY = $i * $buttonAY;
      for ($j=1; $j<=100; $j++) {
        $pushBX = $j * $buttonBX;
        $pushBY = $j * $buttonBY;
        if (($prizeX == $pushAX + $pushBX) && ($prizeY == $pushAY + $pushBY)) {
          $pushMatches[$number][] = array($i, $j);
        }
      }
    }
  }
  foreach ($pushMatches as $gameNum => $matches) {
    $gameCost = ($matches[0][0] * $ACost) + ($matches[0][1] * $BCost);
    $runningSum += $gameCost;
  }
  print "\nTotal token spend: $runningSum\n";
?>