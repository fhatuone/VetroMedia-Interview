<?php

function get_surch($typ) {
    if ($typ === "ZAR") {
        $value = "7.5%";
    } elseif ($typ === "GBP") {
        $value = "5%";
    } elseif ($typ === "EUR") {
        $value = "5%";
    } elseif ($typ === "KES") {
        $value = "2.5%";
    } else {
        $value = "15%";
    }

    return $value;
}

function amnt_pchsd($curr, $amt) {
    $sum = $amt * (1 / $curr);
    return $sum;
}
