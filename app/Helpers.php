<?php
use League\CommonMark\CommonMarkConverter;

function formatRM($amount) {
    return 'RM ' . number_format($amount, 2);
}

function markdownToHtml($text) {
    $converter = new CommonMarkConverter();
    return $converter->convertToHtml($text);
}
