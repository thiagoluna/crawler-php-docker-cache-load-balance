<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Logger;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoggerTest extends TestCase
{
    public function testDebugLogsMessage()
    {
        Log::shouldReceive('debug')
            ->once()
            ->with('Test message');

        Logger::debug('Test message');
    }

    public function testDebugLogsThrowableDetails()
    {
        $throwable = [
            'line' => 123,
            'file' => "test_file.php"
        ];

        Log::shouldReceive('debug')
            ->once()
            ->with('Test error message');

        Log::shouldReceive('debug')
            ->once()
            ->with('Error: Test error message');

        Log::shouldReceive('debug')
            ->once()
            ->with('Line: 123');

        Log::shouldReceive('debug')
            ->once()
            ->with('File: test_file.php');

        Logger::debug('Test error message', $throwable);
    }

    public function testSlackLogsMessageToSlackChannelWhenEnabled()
    {
        config(['logging.channels.slack.enable' => true]);

        Log::shouldReceive('channel')
            ->once()
            ->with('slack')
            ->andReturnSelf();

        Log::shouldReceive('emergency')
            ->once()
            ->with('Test slack message');

        Logger::slack('Test slack message');
    }
}
