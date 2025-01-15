<?php

namespace App\Console\Commands;

use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanOldSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-old-slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina los slots de citas antiguos de forma automatica';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->startOfDay();

        $deletedSlots = Slot::where('date', '<', $today)->delete();
        
        $this->info("Se han eliminado {$deletedSlots} slots antiguos.");
    }
}
