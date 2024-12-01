<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $runningSum = 0;
  $listOne = array();
  $listTwo = array();
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      $inputSplit = explode('   ', $buffer);
      $listOne[] = trim($inputSplit[0]);
      $listTwo[] = trim($inputSplit[1]);
    }
  }
  $counts = array_count_values($listTwo);
  foreach ($listOne as $key => $value) {
    if (array_key_exists($value, $counts)) {
      $runningSum += $value * $counts[$value];
    }
  }
  print "\n" . $runningSum . "\n";
?>