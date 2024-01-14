<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {

        $activities = null;
        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;

        if ($request->has("paginated") && $request->paginated == "true")
            $activities = Activity::paginate($perPage);
        else
            $activities = Activity::get();

        return response()->json($activities);
    }
}
