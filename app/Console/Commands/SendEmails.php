<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Convenia\Pigeon\Facade\Pigeon;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vexpenses:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Pigeon::events('sample.event')
            ->callback(
                Closure::fromCallable([$this, 'httpCallback'])
            )->fallback(
                Closure::fromCallable([$this, 'httpFallback'])
            )->consume(0, true);
    }
}
