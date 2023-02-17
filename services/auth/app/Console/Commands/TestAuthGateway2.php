<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\Gateway\AcceptRefreshTokenJob;
use App\Jobs\Gateway\RefreshTokenJob;
use Illuminate\Console\Command;

final class TestAuthGateway2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:gatewayy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(): void
    {
        RefreshTokenJob::dispatch();
    }
}
