<?php

declare(strict_types=1);

namespace Cortex\Contacts\Console\Commands;

use Rinvex\Contacts\Console\Commands\MigrateCommand as BaseMigrateCommand;

class MigrateCommand extends BaseMigrateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:migrate:contacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Cortex Contacts Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        $this->call('migrate', ['--step' => true, '--path' => 'app/cortex/contacts/database/migrations']);
    }
}
