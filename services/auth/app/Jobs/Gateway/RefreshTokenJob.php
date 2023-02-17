<?php

declare(strict_types=1);

namespace App\Jobs\Gateway;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RefreshTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $result = 'test1',
        private string $result2 = 'test2',
        private string $result3 = 'test3',
    ) {}

    public function handle(): void
    {

    }

    public function hello()
    {
        return 'Oops';
    }
}
