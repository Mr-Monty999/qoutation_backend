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

        $activities = Activity::withCount("services");
        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;

        if ($request->orderByCount && $request->orderByCount == "true")
            $activities->orderBy("services_count", "desc");


        if ($request->has("paginated") && $request->paginated == "true")
            $activities = $activities->paginate($perPage);
        else
            $activities = $activities->get();

        return response()->json($activities);
    }
}
