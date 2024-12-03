<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $safeReports = 0;
  $badReportsToEval = array();
  $reportNumber = 0;
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      $inputSplit = array_map('trim', explode(' ', $buffer));
      $firstKey = array_key_first($inputSplit);
      $runningValue = 0;
      $goodReport = true;
      $badLevels = 0;
      $badLevelKeys = array();
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
              $badLevels++;
              $badLevelKeys[] = $key - 1;
              $goodReport = false;
              continue;
            }
          }
          if ($increasing) {
            if (($runningValue >= $level) || ($level > ($runningValue + 3))) {
              $badLevels++;
              $badLevelKeys[] = $key;
              $goodReport = false;
              break;
            }
          }
          if ($decreasing) {
            if (($runningValue <= $level) || ($level < ($runningValue - 3))) {
              $badLevels++;
              $badLevelKeys[] = $key;
              $goodReport = false;
              break;
            }
          }
          $runningValue = $level;
        }
      }
      if (0 < $badLevels) {
        $badReportsToEval[$reportNumber] = array('report' => $inputSplit, 'badLevels' => $badLevelKeys);
      }
      if ($goodReport) {
        $safeReports++;
      }
      $reportNumber++;
    }
  }
  print "\nSafe after first pass: $safeReports\n";
  foreach ($badReportsToEval as $badReport) {
    $reviewReport = $badReport['report'];
    $reviewReportBefore = $badReport['report'];
    $reviewReportAfter = $badReport['report'];
    unset($reviewReport[$badReport['badLevels'][0]]);
    unset($reviewReportBefore[$badReport['badLevels'][0]-1]);
    unset($reviewReportAfter[$badReport['badLevels'][0]+1]);
    if (reevaluateReport($reviewReport)) {
      $safeReports++;
    } else if (reevaluateReport($reviewReportAfter)) {
      $safeReports++;
    } else if (reevaluateReport($reviewReportBefore)) {
      $safeReports++;
    }
  }
  print "\nSafe after revision pass: $safeReports\n";

  function reevaluateReport(array $reportToReevaluate) : bool {
    $firstKey = array_key_first($reportToReevaluate);
    $runningValue = 0;
    $goodReport = true;
    $increasing = false;
    $decreasing = false;
    $evaluatedReport = array();
    foreach ($reportToReevaluate as $key => $level) {
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
            continue;
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
    return $goodReport;
  }
?>