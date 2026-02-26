<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-event-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        Event::where('status', 'scheduled')
            ->where('publish_at', '<=', $now)
            ->update(['status' => 'published']);

        Event::where('status', 'published')
            ->where('tanggal_waktu_mulai', '<=', $now)
            ->where('tanggal_waktu_selesai', '>=', $now)
            ->update(['status' => 'on_going']);

        Event::whereIn('status', ['published', 'on_going'])
            ->where('tanggal_waktu_selesai', '<', $now)
            ->update(['status' => 'finished']);

        $this->info('Event statuses updated successfully.');
    }
}
