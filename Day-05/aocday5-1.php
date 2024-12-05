<?php
  $fp = @fopen(__DIR__ . '/example.txt', 'r');
  $rules = array();
  $updates = array();
  $runningTotal = 0;
  if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
      if ("\r\n" == $buffer) {
      } else if (1 == preg_match('/\|/', $buffer)) {
        $ruleDefs = explode('|', $buffer);
        $rules[$ruleDefs[0]][] = preg_replace('/\R/', '', $ruleDefs[1]);;
      } else {
        $updates[] = explode(',', $buffer);
      }
    }
  }
  foreach ($updates as $update) {
    $goodOrder = true;
    for ($i = 0; $i < count($update); $i++) {
      $update[$i] = preg_replace('/\R/', '', $update[$i]);
      if (array_key_exists($update[$i], $rules)) {
        foreach ($rules[$update[$i]] as $rule) {
          $updateIndex = array_keys($update, $rule);
          if (!empty($updateIndex)) {
            if ($i > array_keys($update, $rule)[0]) {
              $goodOrder = false;
              break 2;
            }
          }
        }
      }
    }
    if ($goodOrder) {
      $runningTotal += $update[round((count($update)-1)/2)];
    }
  }
  print "Total: $runningTotal\n";
?>