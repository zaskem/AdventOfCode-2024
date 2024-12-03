<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $safeReports = 0;
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      $inputSplit = explode(' ', $buffer);
      $firstKey = array_key_first($inputSplit);
      $runningValue = 0;
      $goodReport = true;
      $increasing = false;
      $decreasing = false;
      foreach ($inputSplit as $key => $level) {
        if ($firstKey == $key) {
          $runningValue = $level; 
        } else {
          if ($increasing === $decreasing) {
            if ($runningValue > $level) {
              $decreasing = true;
            } else if ($runningValue < $level) {
              $increasing = true;
            } else {
              $goodReport = false;
              break;
            }
          }
          if ($increasing) {
            if (($runningValue >= $level) || ($level > ($runningValue + 3))) {
              $goodReport = false;
              break;
            }
          }
          if ($decreasing) {
            if (($runningValue <= $level) || ($level < ($runningValue - 3))) {
              $goodReport = false;
              break;
            }
          }
          $runningValue = $level;
        }
      }
      if ($goodReport) {
        $safeReports++;
      }
    }
  }

  print "\nTotal Safe Reports: " . $safeReports . "\n";
?>