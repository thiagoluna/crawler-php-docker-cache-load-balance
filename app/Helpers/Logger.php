<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function debug(string $message, array $throwable = []): void
    {
        Log::debug($message);

        if ($throwable) {
            Log::debug(sprintf("Error: %s", $message));
            Log::debug(sprintf("Line: %d", $throwable["line"]));
            Log::debug(sprintf("File: %s", $throwable["file"]));
        }
    }

    public static function slack(string $message): void
    {
        if (config('logging.channels.slack.enable')) {
            Log::channel('slack')->emergency($message);
        }
    }
}
