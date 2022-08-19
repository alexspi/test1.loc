<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Services\SearchTown;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    use ApiHelper;

    public function __construct(SearchTown $search)
    {
        $this->search = $search;
    }

    /**
     * Список городов
     * @param Request $request
     * @return JsonResponse
     */
    public function gettown(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->townValidationRules());
        if ($validator->passes()) {

            $name = $request->input('name');
            $listtown = $this->search->getlist($name);

            return $this->onSuccess($listtown, 'Town list');
        }
        return $this->onError(400, $validator->errors());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function applicationlist(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isApplicant($user)) {
            $applications = Application::where('user_id', $user->id)->get();
            return $this->onSuccess($applications, 'Ваши заявки');
        }
        $applications = Application::all();

        return $this->onSuccess($applications, 'Все заявки');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function applicationFilter(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isManager($user)) {
            $applications = Application::all();
            if ($request['status']) {
                $applications = Application::where('status', $request['status'])->get();
            }
            return $this->onSuccess($applications, 'Ваши заявки');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function applicationCreate(Request $request): JsonResponse
    {
        $user = $request->user();
        $directory = 'docs/' . $user->id;
        $doclincs = [];
        if ($this->isApplicant($user)) {
            $validator = Validator::make($request->all(), $this->applicValidationRules());

            if ($validator->passes()) {
                if ($request->hasFile('docs')) {
                    $files = $request->file('docs');
                    foreach ($files as $file) {
                        $fileName = Carbon::now()->toDateString() . '_' . Carbon::now()->format('H-i-s') . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs($directory, $fileName, 'public');
                        $doclincs[] = $filePath;
                    }

                }
                $application = new Application();
                $application->text = $request['text'];
                $application->coordinates = $request['coordinates'];
                $application->user_id = Auth::user()->id;
                $application->user_file_url = $doclincs;
                $application->status = 'new';
                $application->save();
                return $this->onSuccess($application, 'Applic Created');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function applicationDelete(Request $request, $id):JsonResponse
    {
        $user = $request->user();
        if($this->isManager($user)) {
            $applic = Application::find($id);
            if ($applic) {
                $applic->delete();
                if (!empty($applic)){
                    return $this->onSuccess('', 'Applic Deleted');
                }
                return $this->onError(404, 'Applic Not Found');
            }
        }
        return $this->onError(401, 'Unauthorized Access');
    }

}
