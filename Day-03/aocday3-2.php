<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $runningTotal = 0;
  $patternMatches = array();
  $doAction = true;
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      preg_match_all("/(mul\(\d*\,\d*\))|(do\(\))|(don't\(\))/", $buffer, $patternMatches[], PREG_SET_ORDER);
    }
  }
  foreach ($patternMatches as $key => $patternSet) {
    foreach ($patternSet as $key => $pattern) {
      if ("don't()" == $pattern[0]) {
        $doAction = false;
      } else if ("do()" == $pattern[0]) {
        $doAction = true;
      } else {
        if ($doAction) {
          $matchPattern = array();
          preg_match('/\d*,\d*/', $pattern[0], $matchPattern);
          $mult = explode(',', $matchPattern[0]);
          $runningTotal += $mult[0] * $mult[1];
        }
      }
    }
  }
  print "\nTotal of multiplications: " . $runningTotal . "\n";
?>