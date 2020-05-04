<?php

namespace Alfatron\Discuss\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'discuss:setup';

    protected $description = 'Takes the necessary actions to set laravel-discuss up.';

    public function handle()
    {
        $this->output->comment('publish assets');
        $this->output->comment('run migrations');
    }
}
