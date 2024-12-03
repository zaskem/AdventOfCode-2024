<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $runningTotal = 0;
  $patternMatches = array();
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      preg_match_all('/(mul\(\d*,\d*\))/', $buffer, $patternMatches[]);
    }
  }
  foreach ($patternMatches as $key => $patternSet) {
    foreach ($patternSet[0] as $key => $pattern) {
      $matchPattern = array();
      preg_match('/\d*,\d*/', $pattern, $matchPattern);
      $mult = explode(',', $matchPattern[0]);
      $runningTotal += $mult[0] * $mult[1];
    }
  }
  print "\nTotal of multiplications: " . $runningTotal . "\n";
?>