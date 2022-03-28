<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContractResource;
use App\Models\Contract;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractController extends Controller
{
    public function index() :JsonResource
    {
        $contracts = Contract::withCount(['jobs' => function($query) {
            $query->where('is_approved', true);
        }])
        ->get();

        return ContractResource::collection($contracts);
    }
}
