<?php

namespace App\Http\Controllers;

use App\Enums\OsEnum;
use App\Repositories\Device\DeviceRepository;
use App\Services\OS\Type\OsTypeFactory;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException as ValidationExceptionAlias;

class ApiController extends Controller
{
    protected $deviceRepo;

    /**
     * ApiController constructor.
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(DeviceRepository $deviceRepository)
    {
        $this->deviceRepo = $deviceRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationExceptionAlias
     */
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'appId'    => 'required|string',
                'uid'      => 'required|string',
                'language' => 'required|string|max:3',
                'os'       => 'required|in:' . OsEnum::commaSeparated(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'      => false,
                'message'     => $e->getMessage(),
                'validations' => $e->validator->getMessageBag()
            ], 400);
        }

        try {
            DB::beginTransaction();

            $data = [
                'uid'      => $request->input('uid'),
                'language' => $request->input('language'),
                'os'       => $request->input('os'),
                'code'     => $request->input('appId')
            ];

            // check for existence
            $device = $this->deviceRepo->filter($data);
            if ($device && count($device)) {
                return response()->json([
                    'status'       => true,
                    'client-token' => $device->first()->token,
                    'message'      => trans('generals.operation.retrieve.successful'),
                ], 200);
            }

            // otherwise create new one
            $device = $this->deviceRepo->create($data);

            DB::commit();

            return response()->json([
                'status'       => true,
                'client-token' => $device->token,
                'message'      => trans('generals.operation.create.successful'),
            ], 201);

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationExceptionAlias
     */
    public function setSubscription(Request $request)
    {
        try {
            $this->validate($request, [
                'receipt' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'      => false,
                'message'     => $e->getMessage(),
                'validations' => $e->validator->getMessageBag()
            ], 400);
        }

        try {
            // catch it from CheckClientToken middleware
            $deviceId = $request->input('device_id');
            $deviceOs = $request->input('device_os');
            $applicationId = $request->input('application_id');

            // call os api
            $osTypeObj = OsTypeFactory::create($deviceOs, $applicationId);
            $expireDate = $osTypeObj->checkReceipt($request->input('receipt'));

            // update subscription with expire date
            $this->deviceRepo->setSubscription([
                'application_id'           => $applicationId,
                'device_id'                => $deviceId,
                'subscription_expire_date' => $expireDate,
            ]);

            return response()->json(['subscription-expire-date' => $expireDate], 200);

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubscription(Request $request)
    {
        try {
            // catch it from CheckClientToken middleware
            $deviceId = $request->input('device_id');
            $applicationId = $request->input('application_id');

            $result = $this->deviceRepo->getSubscription($deviceId, $applicationId);

            return response()->json(['subscription-status' => $result], 200);

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }

    }

}
