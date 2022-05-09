<?php

namespace Dmpty\LaravelUtilities\Common\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class LogCreate implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    private $class;

    private $data;

    private $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($class, array $data, string $date = '')
    {
        $this->class = $class;
        $this->data = $data;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->class::safeQuery($this->date)->create($this->data);
    }
}
