<?php
function getPercent($number) {
    return sprintf('%.2f', round(($number / TOTAL) * 100, 2)) . "%";
}
?>