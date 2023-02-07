<?php

namespace App\Jobs\Gateway;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AcceptRefreshTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $result = 'OK',
        private string $hello = 'helloPeople',
        private string $hello2 = 'helloPeople2',
    ) {}

    public function handle(): void
    {

    }

    public function hello()
    {
        return 'Oops';
    }
}
