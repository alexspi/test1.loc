<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Services\SearchTown;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TestController extends Controller
{
    use ApiHelper;

    public function __construct(SearchTown $search)
    {
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function town()
    {
        $listtown = [];
        return view('town', ['listtown' => $listtown]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     * @throws GuzzleException
     */
    public function gettown(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
        ]);
        $name = $request->input('name');
        $listtown = $this->search->getlist($name);
        return view('town', ['listtown' => $listtown]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function applicationlist(Request $request)
    {

        if (Auth::user()->role_id == 3) {

            $applications = Application::where('user_id', Auth::user()->id)->get();
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $applications = Application::all();
            if ($request['status']) {
                $applications = Application::where('status', $request['status'])->get();
            }

        } else {
            $applications = [];
        }
        return view('applications/list', ['applications' => $applications]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function applicationFilter(Request $request)
    {

        $applications = Application::all();
        if ($request['status'] & Auth::user()->role_id == 2) {
            $applications = Application::where('status', $request['status'])->get();
        }

        return view('applications/list', ['applications' => $applications]);

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function applicationCreate(Request $request)
    {
        if (Auth::user()->role_id == 3) {

            $directory = 'docs/' . Auth::user()->id;
            $doclincs = [];


            $request->validate([
                'text' => 'required|max:255',
                'coordinates.lon' => ['required', 'regex:/^[-]?((([0-8]?[0-9])(\.(\d{1,8}))?)|(90(\.0+)?))$/i'],
                'coordinates.lat' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,8}))?)|180(\.0+)?)$/i'],
                'docs.*' => 'mimes:pdf,jpg,doc',
                'docs' => 'max:5',
            ]);

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

            return redirect()->route('listapp');
        }
        return redirect()->route('listapp')->withErrors('Недостаточно прав');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function applicationDelete($id)
    {
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $applic = Application::find($id);
            if ($applic) {
                $applic->delete();

            }
            return redirect()->route('listapp');
        }
        return redirect()->route('listapp')->withErrors('Недостаточно прав');
    }

}
