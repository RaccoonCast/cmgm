<?php

function sortFrequencies($frequencies)
{
  /**
   * Given a band object with value (number[]) and uom (string),
   * return the lowest frequency in MHz.
   */
  function getLowInMHz($bandObj): float
  {
    // If frequency is unknown, return 0
    if (!is_array($bandObj->value) || count($bandObj->value) < 1) {
      return 0.0;
    }
    $low = (float) $bandObj->value[0];
    switch (strtolower($bandObj->uom)) {
      case "ghz":
        return $low * 1000; // 1 GHz = 1000 MHz
      case "mhz":
        return $low;
      case "khz":
        return $low / 1000; // 1000 kHz = 1 MHz
      default:
        return $low; // assume MHz
    }
  }

  // Sort so that lowest frequencies appear first
  usort($frequencies, function ($a, $b) {
    $aMHz = getLowInMHz($a->band);
    $bMHz = getLowInMHz($b->band);
    return $aMHz <=> $bMHz;
  });

  return $frequencies;
}
