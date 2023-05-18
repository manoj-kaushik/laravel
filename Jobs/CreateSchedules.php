<?php

namespace Modules\Demowebinar\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Artisan;

class CreateSchedules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    protected $seriesId, $isEdit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($seriesId, $isEdit = false)
    {
        $this->seriesId = $seriesId;
        $this->isEdit = $isEdit;
        $this->queue = 'gw_events';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('gw:create_schedule_per_series', [
            'id' => $this->seriesId,
            'isEdit' => $this->isEdit
        ]);
    }
}
