<?php

namespace App\Jobs;

use App\Helpers\Logger;
use AllowDynamicProperties;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class WriteThrowableLogsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(readonly array $throwable, readonly bool $slack = false)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Logger::debug($this->throwable["message"], $this->throwable);

        if ($this->slack) Logger::slack($this->throwable["message"]);
    }
}
