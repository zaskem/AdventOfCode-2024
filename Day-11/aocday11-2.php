<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $stoneCount = 0;
  $blinkCount = 75;
  $bucket = array();
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      $initialStones = explode(' ', $buffer);
      foreach ($initialStones as $stone) {
        $stoneCount += recursiveStoneBlink($stone, $blinkCount);
      }
    }
  }
  print "\nTotal stone count: $stoneCount\n";

  function recursiveStoneBlink(string $currentStone, int $blinks) : int {
    global $bucket;
    if (isset($bucket[$currentStone][$blinks])) {
      return $bucket[$currentStone][$blinks];
    }
    if (0 == $blinks) {
      // Reached the end, only one left
      return 1;
    }
    if ('0' == $currentStone) {
      // First rule
      $recursiveStones = recursiveStoneBlink('1', $blinks - 1);
    } else if (0 == strlen($currentStone) % 2) {
      // Second rule
      $midpoint = strlen($currentStone) / 2;
      $halfStone1 = substr($currentStone, 0, $midpoint);
      // Need double casting to trim all leading zeroes
      $halfStone2 = (string) ((int) substr($currentStone, $midpoint));
      $recursiveStones = recursiveStoneBlink($halfStone1, $blinks - 1) + recursiveStoneBlink($halfStone2, $blinks - 1);
    } else {
      // Default rule
      $recursiveStones = recursiveStoneBlink($currentStone * 2024, $blinks - 1);
    }
    $bucket[$currentStone][$blinks] = $recursiveStones;
    return $recursiveStones;
  }
?>