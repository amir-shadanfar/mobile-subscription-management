<?php

namespace App\Jobs;

use App\Device;
use App\Repositories\Device\DeviceRepository;
use App\Services\OS\Type\OsTypeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $device;
    public $deviceRepository;

    /**
     * SubscriptionCheckJob constructor.
     * @param Device $device
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(Device $device, DeviceRepository $deviceRepository)
    {
        $this->queue = 'subscription';
        $this->device = $device;
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * get last status of subscription from api and update db
     */
    public function handle()
    {
        try {

            $application = $this->device->applications->first();

            // Factory
            $osTypeObj = OsTypeFactory::create($this->device->os, $application->id);
            $expireDateStatus = $osTypeObj->getSubscription($this->device->token);

            $pivot = $application->pivot;

            if ($pivot->subscriptio_status !== $expireDateStatus) {

                $this->deviceRepository->setSubscription([
                    'application_id'      => $this->device->applications->first()->id,
                    'device_id'           => $this->device->id,
                    'subscription_status' => $expireDateStatus,
                ]);

            }

        } catch (\Throwable $e) {
            // it is caused because of rate-limit in calling API
            // retry hourly
            if ($this->attempts() < 24) {
                $delayInSeconds = 60 * 60;
                $this->release($delayInSeconds);
            }
        }
    }
}
