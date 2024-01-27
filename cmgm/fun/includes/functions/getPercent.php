<?php
function getPercent($number) {
        return number_format(($number / TOTAL) * 100, 2) . '%';
    }
?>