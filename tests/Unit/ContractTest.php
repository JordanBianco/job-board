<?php

namespace Tests\Unit;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_has_many_job_advertisement()
    {
        $contract = Contract::factory()->create();

        $this->assertInstanceOf(Collection::class, $contract->jobs);
    }
}
