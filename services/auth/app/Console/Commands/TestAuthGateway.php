<?php

namespace App\Console\Commands;

use App\Jobs\Gateway\AcceptRefreshTokenJob;
use Illuminate\Console\Command;

class TestAuthGateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:gateway';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(): void
    {
        AcceptRefreshTokenJob::dispatch();
    }
}
