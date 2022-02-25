<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobController extends Controller
{
    public function index(Request $request) :JsonResource
    {
        $search = $request->search ?? '';
        $sort = $request->sort ?? 'latest';
        $contract = $request->contract ?? '';
        $working_day = $request->working_day ?? '';

        return JobResource::collection(
            Job::withSearch($search)
                ->withContractType($contract)
                ->withWorkingDay($working_day)
                ->withSort($sort)
                ->where('is_approved', true)
                ->with('tags')
                ->paginate(10)
        );
    }

    public function show(Job $job) :JsonResource
    {
        return new JobResource($job->loadMissing('tags'));
    }
}
