<?php

function is_selected($option_value, $true_value) {
    return $option_value == $true_value ? ' selected' : '';
}