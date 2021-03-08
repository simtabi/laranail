<?php

namespace Simtabi\Laranail\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Simtabi\Laranail\Supports\LaravelTools;

class TidyApplicationCommand extends Command
{

    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'tidy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize CMS with some shakes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting tidying process...');

        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('optimize:clear');

        $this->call('optimize');
        $this->call('route:cache');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('config:cache');
        $this->call('clear-compiled');

        $this->info('Tidying process completed successfully!');

        LaravelTools::clearCache();

        return 0;
    }
}
