<?php
  $inputMap = array_map('str_split', file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES));
  $height = count($inputMap);
  $width = count($inputMap[0]);
  $visitedPositions = array();
  $xObstacles = array();
  $yObstacles = array();
  $guardPoint = array(0,0);
  $currentDirection = '^';
  $exitFound = false;
  foreach ($inputMap as $y => $row) {
    foreach ($row as $x => $value) {
      if ('#' == $value) {
        $xObstacles[$x][$y] = $y;
        $yObstacles[$y][$x] = $x;
      }
      if ('^' == $value) {
        $guardPoint = [$x, $y];
        @$visitedPositions["$x-$y"]++;
      }
    }
  }
  while (!$exitFound) {
    switch($currentDirection) {
      case '^':
        $nextObstacle = null;
        $currentX = $guardPoint[0];
        $currentGuardY = $guardPoint[1];
        if (max($xObstacles[$currentX]) < $currentGuardY) {
          $nextObstacle = max($xObstacles[$currentX]);
          $guardPoint = array($currentX,$nextObstacle+1);
        } else {
          foreach (array_reverse($xObstacles[$currentX]) as $point => $value) {
            if ($value < $currentGuardY) {
              $nextObstacle = $value;
              $guardPoint = array($currentX,$nextObstacle+1);
              break;
            }
          }
        }
        if (is_null($nextObstacle)) {
          for ($i=0; $i < $currentGuardY; $i++) {
            @$visitedPositions["$currentX-$i"]++;
          }  
          $exitFound = true;
          break;
        }
        for ($i=$nextObstacle+1; $i < $currentGuardY; $i++) {
          @$visitedPositions["$currentX-$i"]++;
        }
        $currentDirection = '>';
        break;
      case '>':
        $nextObstacle = null;
        $currentGuardX = $guardPoint[0];
        $currentY = $guardPoint[1];
        if (min($yObstacles[$currentY]) > $currentGuardX) {
          $nextObstacle = min($yObstacles[$currentY]);
          $guardPoint = array($nextObstacle-1,$currentY);
        } else {
          foreach ($yObstacles[$currentY] as $point => $value) {
            if ($value > $currentGuardX) {
              $nextObstacle = $value;
              $guardPoint = array($nextObstacle-1,$currentY);
              break;
            }
          }
        }
        if (is_null($nextObstacle)) {
          for ($i=$currentGuardX+1; $i < $width; $i++) {
            @$visitedPositions["$i-$currentY"]++;
          }  
          $exitFound = true;
          break;
        }
        for ($i=$currentGuardX+1; $i < $nextObstacle; $i++) {
          @$visitedPositions["$i-$currentY"]++;
        }
        $currentDirection = 'v';
        break;
      case 'v':
        $nextObstacle = null;
        $currentX = $guardPoint[0];
        $currentGuardY = $guardPoint[1];
        if (min($xObstacles[$currentX]) > $currentGuardY) {
          $nextObstacle = min($xObstacles[$currentX]);
          $guardPoint = array($currentX,$nextObstacle-1);
        } else {
          foreach ($xObstacles[$currentX] as $point => $value) {
            if ($value > $currentGuardY) {
              $nextObstacle = $value;
              $guardPoint = array($currentX,$nextObstacle-1);
              break;
            }
          }
        }
        if (is_null($nextObstacle)) {
          for ($i=$currentGuardY+1; $i < $height; $i++) {
            @$visitedPositions["$currentX-$i"]++;
          }  
          $exitFound = true;
          break;
        }
        for ($i=$currentGuardY+1; $i < $nextObstacle; $i++) {
          @$visitedPositions["$currentX-$i"]++;
        }
        $currentDirection = '<';
        break;
      case '<':
        $nextObstacle = null;
        $currentGuardX = $guardPoint[0];
        $currentY = $guardPoint[1];
        if (max($yObstacles[$currentY]) < $currentGuardX) {
          $nextObstacle = max($yObstacles[$currentY]);
          $guardPoint = array($nextObstacle+1,$currentY);
        } else {
          foreach (array_reverse($yObstacles[$currentY]) as $point => $value) {
            if ($value < $currentGuardX) {
              $nextObstacle = $value;
              $guardPoint = array($nextObstacle+1,$currentY);
              break;
            }
          }
        }
        if (is_null($nextObstacle)) {
          for ($i=0; $i < $currentGuardX; $i++) {
            @$visitedPositions["$i-$currentY"]++;
          }
          $exitFound = true;
          break;
        }
        for ($i=$nextObstacle+1; $i < $currentGuardX; $i++) {
          @$visitedPositions["$i-$currentY"]++;
        }
        $currentDirection = '^';
        break;
    }
  }
  print "\nCount of distinct positions: ".count($visitedPositions)."\n";
?>