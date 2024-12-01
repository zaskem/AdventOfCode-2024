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
  sort($listOne);
  sort($listTwo);
  foreach ($listOne as $key => $value) {
    $runningSum += abs($value - $listTwo[$key]);
  }
  print "\n" . $runningSum . "\n";
?>