<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $runningSum = 0;
  $testValues = array();
  $equationValues = array();
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      $testValues = explode(': ', $buffer);
      $equationValues = array_reverse(explode(' ', rtrim($testValues[1])));
      $desiredValue = $testValues[0];
      $currentNumber = array_pop($equationValues);
      if (evaluateEquation($equationValues, $currentNumber, $desiredValue)) {
        $runningSum += $desiredValue;
      }
    }
  }
  print "\nTotal calibration result: $runningSum\n";

  function evaluateEquation(array $values, int $currentValue, int $desiredValue) : bool {
    if (0 == count($values)) {
      // Reached the end, compare the result
      return $currentValue == $desiredValue;
    }
    if ($currentValue > $desiredValue) {
      // Too big...automatically return false
      return false;
    }
    $nextValue = array_pop($values);
    return (evaluateEquation($values, $currentValue * $nextValue, $desiredValue) || evaluateEquation($values, $currentValue + $nextValue, $desiredValue));
  }
?>