<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class GenerateSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera los slots de citas para las proximas semanas en base a los horarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedules = Schedule::where('is_active', true)->get();

        $startDate  = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addWeeks(2)->endOfDay();

        foreach ($schedules as $schedule) {
            $dayOfWeek = $schedule->day_of_week;
            Log::info('Generando slots para el veterinario ' . $schedule->vet_id . ' el día ' . $dayOfWeek);
            
            $date = $startDate->copy()->next($dayOfWeek);
            while ($date <= $endDate) {
                $startTime = Carbon::parse($schedule->start_time);
                $endTime = Carbon::parse($schedule->end_time);
                

        
                while ($startTime < $endTime) {
                    $startSlot = $startTime->format('H:i:s');
                    $endSlot = $startTime->copy()->addMinutes(30)->format('H:i:s');
        
                    if (!Slot::where([
                        'vet_id' => $schedule->vet_id,
                        'date' => $date->toDateString(),
                        'start_time' => $startSlot,
                        'end_time' => $endSlot,
                        'schedule_id' => $schedule->id,
                    ])->exists()) {
                        Slot::create([
                            'vet_id' => $schedule->vet_id,
                            'date' => $date->toDateString(),
                            'start_time' => $startSlot,
                            'end_time' => $endSlot,
                            'status' => 'available',
                            'schedule_id' => $schedule->id,
                        ]);
                    }
        
                    $startTime->addMinutes(30);
                }
        
                $date->addWeek(); // Avanza a la próxima semana
                Log::info('Generados slots para el día ' . $date->toDateString());
            }
        }

        $this->info('Slots generados para las próximas dos semanas.');
        
    } // fin de la función handle
}
