<?php

namespace App\Http\Middleware;

use App\Device;
use App\Repositories\Device\DeviceRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckClientToken
{
    protected $deviceRepo;

    public function __construct(DeviceRepository $deviceRepository)
    {
        $this->deviceRepo = $deviceRepository;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return Application|ResponseFactory|Response|mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('client-token');
            if (!empty($token)) {
                // check existence token on cache
                if (!Cache::has($token)) {

                    $devices = $this->deviceRepo->filter([
                        'token' => $token
                    ], ['applications']);

                    if (!$devices) {
                        throw new \Exception('The given token is not identified', 400);
                    } else {
                        $device = $devices->first();
                        Cache::put($token, [
                            'device_id'      => $device->id,
                            'device_os'      => $device->os,
                            'application_id' => $device->applications->first()->id,
                        ], 3600);// 1 hour
                    }
                }

                $cachedDeviceInfo = Cache::get($token);
                $request->request->add($cachedDeviceInfo);

            } else {
                return response('Authorization not sent', 401);
            }
        } catch (\Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }

        return $next($request);
    }
}
