<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Http\Resources\UserResource;
use App\Models\Job;

class UserController extends Controller
{
    public function index()
    {
        return new UserResource(auth()->user());
    }

    public function jobs()
    {
        $jobs = Job::where('user_id', auth()->id())
            ->with('tags', 'contract:id,name')
            ->latest()
            ->paginate(10);

        return JobResource::collection($jobs);
    }
}
