<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $directions = array('N','NE','E','SE','S','SW','W','NW');
  $mapData = array();
  $startPoints = array();
  $matches = 0;
  if ($fp) {
    $yCoord = 0;
    while (($buffer = fgets($fp, 4096)) !== false) {
      $values = rtrim($buffer);
      for ($xCoord = 0; $xCoord < strlen($values); $xCoord++) {
        if ('X' == $values[$xCoord]) {
          $startPoints[] = array($xCoord, $yCoord);
        }
        $mapData[$xCoord][$yCoord] = $values[$xCoord];
      }
      $yCoord++;
    }
  }
  foreach ($startPoints as $startPoint) {
    foreach ($directions as $direction) {
      if (directionalSearch($startPoint, $direction)) {
        $matches++;
      }
    }
  }
  print "\nTotal matches found: $matches\n";

  function directionalSearch(array $startPoint, string $cardinal) : bool {
    global $mapData;
    $pattern = array('X','M','A','S');
    switch($cardinal) {
      case 'N':
        $xDir = 0;
        $yDir = -1;
        break;
      case 'NE':
        $xDir = 1;
        $yDir = -1;
        break;
      case 'E':
        $xDir = 1;
        $yDir = 0;
        break;
      case 'SE':
        $xDir = 1;
        $yDir = 1;
        break;
      case 'S':
        $xDir = 0;
        $yDir = 1;
        break;
      case 'SW':
        $xDir = -1;
        $yDir = 1;
        break;
      case 'W':
        $xDir = -1;
        $yDir = 0;
        break;
      case 'NW':
        $xDir = -1;
        $yDir = -1;
        break;
    }
    $iteration = 0;
    $goodSearch = true;
    foreach ($pattern as $letter) {
      $xC = ($xDir * $iteration) + $startPoint[0];
      $yC = ($yDir * $iteration) + $startPoint[1];
      if ($letter != @$mapData[$xC][$yC]) {
        $goodSearch = false;
        break;
      }
      $iteration++;
    }
  return $goodSearch;
  }
?>