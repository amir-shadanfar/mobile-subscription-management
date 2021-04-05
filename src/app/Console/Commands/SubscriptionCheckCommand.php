<?php

namespace App\Console\Commands;

use App\Application;
use App\Device;
use App\Enums\SubscriptionStatusEnum;
use App\Jobs\SubscriptionCheckJob;
use App\Repositories\Device\DeviceRepository;
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

    public $deviceRepository;

    /**
     * SubscriptionCheckCommand constructor.
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(DeviceRepository $deviceRepository)
    {
        parent::__construct();
        $this->deviceRepository = $deviceRepository;
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
                ->chunk(1000, function ($devices) {
                    foreach ($devices as $device) {
                        if ($device->applications->first()) {
                            $now = Carbon::now();// UTC

                            $pivot = $device->applications->first()->pivot;

                            if (!is_null($pivot->subscription_expire_date) && ($pivot->subscription_status !== SubscriptionStatusEnum::CANCELED) && $now->greaterThanOrEqualTo($pivot->subscription_expire_date)) {
                                dispatch(new SubscriptionCheckJob($device, $this->deviceRepository));
                            }
                        }
                    }
                });
        }

    }
}
