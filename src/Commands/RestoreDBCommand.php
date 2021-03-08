<?php

namespace Simtabi\Laranail\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Simtabi\Laranail\Supports\LaravelTools;

class RestoreDBCommand extends Command
{

    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        if ($this->clearCache()) {
            $this->info('Starting DB restore process...');

            $this->call('migrate:fresh');
            $this->call('db:seed');

            $this->info('Finished DB restoration successfully!');
            $this->call('tidy');
        }

        return 0;
    }

    private function clearCache(){
        $this->call('tidy');
        if (LaravelTools::clearCache()) {
            $this->info('Successfully cleared application Cache!');
        }
        return true;
    }
}
