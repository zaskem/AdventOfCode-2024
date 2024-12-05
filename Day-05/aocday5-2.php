<?php
  $fp = @fopen(__DIR__ . '/input.txt', 'r');
  $rules = array();
  $updates = array();
  $runningTotal = 0;
  $badUpdates = array();
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
    $counter = 0;
    for ($i = 0; $i < count($update); $i++) {
      $update[$i] = preg_replace('/\R/', '', $update[$i]);
      if (array_key_exists($update[$i], $rules)) {
        foreach ($rules[$update[$i]] as $rule) {
          $updateIndex = array_keys($update, $rule);
          if (!empty($updateIndex)) {
            if ($i > array_keys($update, $rule)[0]) {
              $goodOrder = false;
              $counter++;
            }
          }
        }
      }
    }
    if (!$goodOrder) {
      $badUpdates[] = array('errorcount'=>$counter, 'update'=>$update);
    }
  }
  $correctedUpdates = array();
  foreach ($badUpdates as $id => $badUpdate) {
    $errorCount = $badUpdate['errorcount'];
    $update = $badUpdate['update'];
    $goodUpdate = false;
    $j = 0;
    while ((!$goodUpdate) && ($j < $errorCount)) {
      for ($i = 0; $i < count($update); $i++) {
        $update[$i] = preg_replace('/\R/', '', $update[$i]);
        if (array_key_exists($update[$i], $rules)) {
          foreach ($rules[$update[$i]] as $rule) {
            $updateIndex = array_keys($update, $rule);
            if (!empty($updateIndex)) {
              if ($i > array_keys($update, $rule)[0]) {
                $conflictingItem = array_keys($update, $rule)[0];
                $itemToMove = $update[$i];
                $update[$i] = $update[$conflictingItem];
                $update[$conflictingItem] = $itemToMove;
                $counter++;
              }
              if (checkUpdate($update)) {
                $correctedUpdates[] = $update;
                $goodUpdate = true;
                break 3;
              }
            }
          }
        }
      }
      $j++;
    }
  }
  foreach ($correctedUpdates as $correctedUpdate) {
    $runningTotal += $correctedUpdate[round((count($correctedUpdate)-1)/2)];
  }
  print "Total: $runningTotal\n";

  function checkUpdate(array $update) : bool {
    global $rules;
    $goodOrder = true;
    for ($i = 0; $i < count($update); $i++) {
      $update[$i] = preg_replace('/\R/', '', $update[$i]);
      if (array_key_exists($update[$i], $rules)) {
        foreach ($rules[$update[$i]] as $rule) {
          $updateIndex = array_keys($update, $rule);
          if (!empty($updateIndex)) {
            if ($i > array_keys($update, $rule)[0]) {
              $goodOrder = false;
            }
          }
        }
      }
    }
    return $goodOrder;
  }
?>