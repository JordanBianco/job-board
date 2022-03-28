<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index(Request $request) :JsonResource
    {
        $search = $request->search ?? '';
        $sort = $request->sort ?? 'latest';
        $contract = $request->contract ?? '';
        $working_day = $request->working_day ?? '';
        $remote_working = $request->remote_working ?? '';
        $tags = $request->tags ?? '';
        
        return JobResource::collection(
            Job::withSearch($search)
                ->withContractType($contract)
                ->withWorkingDay($working_day)
                ->withRemoteWorking($remote_working)
                ->withTags($tags)
                ->withSort($sort)
                ->where('is_approved', true)
                ->with('tags', 'contract:id,name')
                ->paginate(10)
        );
    }

    public function show(Job $job) :JsonResource
    {
        return new JobResource($job->loadMissing('contract:id,name', 'tags'));
    }

    public function store(StoreJobRequest $request)
    {
        $validated = $request->validated();

        $job = DB::transaction(function() use($validated) {

            $job = auth()->user()->jobs()->create([
                'contract_id' => $validated['contract_id'],
                'position' => $validated['position'],
                'location' => $validated['location'],
                'remote_working' => $validated['remote_working'] === 'false' ? 0 : 1,
                'working_day' => $validated['working_day'],
                'company' => $validated['company'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'salary' => $validated['salary'],
                'apply_link' => $validated['apply_link'],
                'is_approved' => false
            ]);
    
            if ($validated['tags']) {
                $tags = explode(',', $validated['tags']);
                $job->tags()->attach($tags);
            }

            if ($validated['logo']) {
                $path = $validated['logo']->storePublicly('/',  ['disk' => 'public']);
                $job->logo = $path;
                $job->save();
            }
    
            return $job;

            // Notify Admin
        });

        return new JobResource($job->loadMissing('contract:id,name', 'tags'));
    }

    public function update(UpdateJobRequest $request, Job $job)
    {        
        abort_if(auth()->id() != $job->user_id, 403);

        $validated = $request->validated();

        $job->update([
            'contract_id' => $validated['contract_id'],
            'position' => $validated['position'],
            'location' => $validated['location'],
            'remote_working' => $validated['remote_working'] === 'false' ? 0 : 1,
            'working_day' => $validated['working_day'],
            'company' => $validated['company'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'salary' => $validated['salary'], 
            'apply_link' => $validated['apply_link']
        ]);

        if ($validated['tags']) {
            $tags = explode(',', $validated['tags']);
            $job->tags()->sync($tags);
        }

        // Se non è una stringa si procede
        if (!is_string($request->logo)) {
            // se non è null, è stato caricato un file
            if (! is_null($request->logo)) {
                // Se è un file lo valido e lo aggiungo
                $request->validate([
                    'logo' => ['file', 'mimes:jpg,png,jpeg', 'max:5000']
                ]);

                $path = $request['logo']->storePublicly('/',  ['disk' => 'public']);
                $job->logo = $path;
                $job->save();
            }
        }

        return new JobResource($job->loadMissing('contract:id,name', 'tags'));
    }

    public function destroy(Job $job)
    {
        abort_if(auth()->id() != $job->user_id, 403);
        
        $job->delete();

        return response()->json([], 200);
    }
}
