<?php

namespace App\Repositories\Device;

use App\Device;
use App\Enums\SubscriptionStatusEnum;
use App\Repositories\Application\ApplicationRepository;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceRepository extends Repository
{
    protected $applicationRepo;

    /**
     * DeviceRepository constructor.
     * @param Device $model
     * @param ApplicationRepository $applicationRepository
     */
    public function __construct(Device $model, ApplicationRepository $applicationRepository)
    {
        parent::__construct($model);
        $this->applicationRepo = $applicationRepository;
    }

    public function create(array $data): Model
    {
        $data['token'] = sha1(Str::random(60) . time());
        $device = parent::create($data);

        $application = $this->applicationRepo->find($data['code']);
        $device->applications()->attach($application->id);

        return $device;
    }

    /**
     * @param int $deviceId
     * @param int $applicationId
     * @return bool
     */
    public function getSubscription(int $deviceId, int $applicationId)
    {
        $device = Device::with(['applications' => function ($q) use ($applicationId) {
            $q->where('id', $applicationId);
        }])
            ->where('id', $deviceId)
            ->whereHas('applications', function ($q) use ($applicationId) {
                return $q->where('id', $applicationId);
            })
            ->first();

        $pivot = $device->applications->first()->pivot;

        return is_null($pivot->subscription_status) ? false : $pivot->subscription_status;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function setSubscription(array $data)
    {
        $device = Device::with(['applications' => function ($q) use ($data) {
            $q->where('id', $data['application_id']);
        }])
            ->where('id', $data['device_id'])
            ->first();

        $pivot = $device->applications->first()->pivot;

        DB::table('applications_devices')
            ->where('device_id', $data['device_id'])
            ->where('application_id', $data['application_id'])
            ->update([
                'subscription_status'      => is_null($pivot->subscription_expire_date) ? SubscriptionStatusEnum::STARTED : SubscriptionStatusEnum::RENEWED,
                'subscription_expire_date' => $data['subscription_expire_date']
            ]);
    }

}
