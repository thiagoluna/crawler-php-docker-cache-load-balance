<?php

namespace Tests\Unit\Jobs;

use App\Jobs\WriteThrowableLogsJob;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WriteThrowableLogsJobTest extends TestCase
{
    public function testHandleThrowableLogs(): void
    {
        $throwable = [
            'message' => 'Test error message',
            'line' => 123,
            'file' => 'test_file.php'
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

        $job = new WriteThrowableLogsJob($throwable, false);
        $job->handle();
    }

    public function testHandleThrowableLogsWithSlack()
    {
        config(['logging.channels.slack.enable' => true]);

        $throwable = [
            'message' => 'Test error message',
            'line' => 123,
            'file' => 'test_file.php'
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

        Log::shouldReceive('channel')
            ->once()
            ->with('slack')
            ->andReturnSelf();

        Log::shouldReceive('emergency')
            ->once()
            ->with('Test error message');

        $job = new WriteThrowableLogsJob($throwable, true);
        $job->handle();
    }
}
