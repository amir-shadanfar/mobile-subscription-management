<?php

namespace App\Console\Commands;

use App\Application;
use App\Device;
use App\Enums\SubscriptionStatusEnum;
use App\Jobs\SubscriptionCheckJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all subscriptions expire date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function handle()
    {
        foreach (Application::all() as $application) {

            Device::with(['applications' => function ($q) use ($application) {
                $q->where('id', $application->id);
            }])
                ->chunk(1000, function ($device) {

                    $now = Carbon::now();
                    $pivot = $device->application->first()->pivot;

                    if (!is_null($pivot->subscription_expire_date) && ($pivot->subscription_status !== SubscriptionStatusEnum::CANCELED) && $now->greaterThanOrEqualTo($pivot->subscription_expire_date)) {
                        dispatch(new SubscriptionCheckJob($device));
                    }

                });
        }

    }
}
