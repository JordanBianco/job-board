<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContractResource;
use App\Models\Contract;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractController extends Controller
{
    public function index() :JsonResource
    {
        return ContractResource::collection(Contract::withCount('jobs')->get());
    }
}
