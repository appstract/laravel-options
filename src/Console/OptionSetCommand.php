<?php

namespace Appstract\Options\Console;

use Appstract\Options\OptionFacade as Option;
use Illuminate\Console\Command;

class OptionSetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'option:set
                            {key : Option key}
                            {value : Option value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an option.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Option::set($this->argument('key'), $this->argument('value'));

        $this->info('Option added.');
    }
}
