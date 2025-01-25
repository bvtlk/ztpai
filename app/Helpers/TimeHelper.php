<?php

namespace App\Helpers;

use DateTime;

class TimeHelper
{
    public static function timeAgo(string $date): string
    {
        $now = new DateTime();
        $past = new DateTime($date);
        $diff = $now->diff($past);

        $seconds = $diff->s + $diff->i * 60 + $diff->h * 60 * 60 + $diff->d * 24 * 60 * 60;
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $weeks = floor($days / 7);

        if ($weeks > 0) {
            return "$weeks week(s) ago";
        } elseif ($days > 0) {
            return "$days day(s) ago";
        } elseif ($hours > 0) {
            return "$hours hour(s) ago";
        } elseif ($minutes > 0) {
            return "$minutes minute(s) ago";
        } else {
            return "$seconds second(s) ago";
        }
    }
}
