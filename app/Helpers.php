<?php

use League\CommonMark\CommonMarkConverter;
use Carbon\Carbon;

/**
 * Format an amount to Malaysian Ringgit (RM) currency.
 *
 * @param float|int $amount The amount to be formatted.
 * @return string Formatted currency in RM.
 */
function formatRM($amount) {
    return 'RM ' . number_format($amount, 2);
}

/**
 * Convert Markdown text to HTML using CommonMark.
 *
 * @param string $text The Markdown text to convert.
 * @return string The converted HTML string.
 */
function markdownToHtml($text) {
    $converter = new CommonMarkConverter();
    return $converter->convertToHtml($text);
}

/**
 * Format a given date into a specified format.
 *
 * @param string|\DateTimeInterface $date The date to format.
 * @param string $format The format to use (default: 'j/n/Y g:i A').
 * @return string The formatted date.
 */
function formatDate($date) {
    // Parse the date using Carbon
    $carbonDate = Carbon::parse($date);

    // Format date and time separately
    $dateFormat = $carbonDate->format('j/n/Y');  // e.g., '7/12/2024'
    $timeFormat = $carbonDate->format('g:i A');  // e.g., '12:10 AM'

    // Return formatted date with line break between date and time
    return $dateFormat . "<br>" . $timeFormat;
}

/**
 * Get an array of month names.
 *
 * @return array An associative array of month numbers and their corresponding names.
 */
function getMonthNames() {
    return [
        '01' => __('cashflow.01'),
        '02' => __('cashflow.02'),
        '03' => __('cashflow.03'),
        '04' => __('cashflow.04'),
        '05' => __('cashflow.05'),
        '06' => __('cashflow.06'),
        '07' => __('cashflow.07'),
        '08' => __('cashflow.08'),
        '09' => __('cashflow.09'),
        '10' => __('cashflow.10'),
        '11' => __('cashflow.11'),
        '12' => __('cashflow.12'),
    ];
}
