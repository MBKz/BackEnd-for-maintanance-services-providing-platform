<?php

namespace App\Console\Commands;

use App\Models\Proposal as ModelsProposal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Proposal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proposal:delete';

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
        $proposal = ModelsProposal::where('date','<',Carbon::now()->subMinute(1))
        ->where('state_id',1)->first();
        $proposal->delete();
    }
}
