<?php

namespace Naweown\Console\Commands;

use Illuminate\Console\Command;

class CreateSuperCowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supercow:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin account. There can only be one';

    public function handle()
    {
        $fullName = $this->ask("Please provide your fullname");
        $userName = $this->ask("Please provide a username.");
        $emailAddress = $this->ask("Please provide your email address");
    }
}
