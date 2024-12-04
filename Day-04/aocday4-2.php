<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $mapData = array();
  $startPoints = array();
  $matches = 0;
  if ($fp) {
    $yCoord = 0;
    while (($buffer = fgets($fp, 4096)) !== false) {
      $values = rtrim($buffer);
      for ($xCoord = 0; $xCoord < strlen($values); $xCoord++) {
        if ('A' == $values[$xCoord]) {
          $startPoints[] = array($xCoord, $yCoord);
        }
        $mapData[$xCoord][$yCoord] = $values[$xCoord];
      }
      $yCoord++;
    }
  }
  foreach ($startPoints as $startPoint) {
    if (
      ((('M' == lookupCoordinate($startPoint, 'NE')) && ('S' == lookupCoordinate($startPoint, 'SW'))) || 
        (('S' == lookupCoordinate($startPoint, 'NE')) && ('M' == lookupCoordinate($startPoint, 'SW')))) && 
      ((('M' == lookupCoordinate($startPoint, 'NW')) && ('S' == lookupCoordinate($startPoint, 'SE'))) || 
        (('S' == lookupCoordinate($startPoint, 'NW')) && ('M' == lookupCoordinate($startPoint, 'SE'))))
      ) {
      $matches++;
    }
  }
  print "\nTotal X-MAS's found: $matches\n";

  function lookupCoordinate(array $point, string $cardinal) : string {
    global $mapData;
    switch($cardinal) {
      case 'NE':
        $dX = $point[0] + 1;
        $dY = $point[1] - 1;
        break;
      case 'SE':
        $dX = $point[0] + 1;
        $dY = $point[1] + 1;
        break;
      case 'SW':
        $dX = $point[0] - 1;
        $dY = $point[1] + 1;
        break;
      case 'NW':
        $dX = $point[0] - 1;
        $dY = $point[1] - 1;
        break;
    }
    if (null === @$mapData[$dX][$dY]) {
      return '';
    } else {
      return $mapData[$dX][$dY];
    }
  }
?>