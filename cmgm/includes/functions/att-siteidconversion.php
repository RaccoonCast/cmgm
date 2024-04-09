<?php

$mapping = array(
    "CSL" => 75,
    "CVL" => 67,
    "CCL" => 66,
    "CLL" => 55,
    "CLU" => 55,
    "SD" => 31,
    "MA" => 6,
    "MAL" => 6,
    "CT" => 5,
    "CTL" => 5
);


function replaceQuery($query, $mapping) {
    foreach ($mapping as $key => $value) {
        if (strpos($query, $key) === 0) {
            $result = $value . substr($query, -4);
            return $result;
        }
    }
    // If no match is found in the loop, return "no-att"
    return "no-att";
}

$result = replaceQuery(@$_GET['q'], $mapping);
if ($result !== "no-att") redir ("https://www.cellmapper.net/map?MCC=310&MNC=410&type=LTE&ppT=" . $result . "&ppL=0", "0") ;
?>