<?php
$addr_zip = $zip;
$addr_streettype = strrchr($string,' ');
$addr_num strtok($addr_streetname, " ");
if (str_contains(' W ', $addr_streetname)) $addr_dir = "W";
if (str_contains(' E ', $addr_streetname)) $addr_dir = "E";
if (str_contains(' S ', $addr_streetname)) $addr_dir = "S";
if (str_contains(' N ', $addr_streetname)) $addr_dir = "N";
if (empty($addr_dir)) $addr_dir = null;

SPS($addr_num,$addr_dir,$addr_streetname)

function SPS($addr_num,$addr_dir,$addr_streetname,$addr_streettype,$addr_zip,$city) {
if ($city == "Los Angeles") {
  $url = "https://www.permitla.org/ipars/list_permit.cfm?RANGE_STR=$addr_num&RANGE_END=$addr_num&STR_DIR=$addr_dir&FRAC_STR=&FRAC_END=&STR_NAME=$addr_streetname&STR_SUFF=$addr_streettype&SUFF_DIR=&UNIT_STR=&UNIT_END=&ZIP=$addr_zip&ADDR=$addr_num%20%20$addr_dir%20$addr_streetname%20$addr_streettype%20%20$addr_zip";
}
}
?>
