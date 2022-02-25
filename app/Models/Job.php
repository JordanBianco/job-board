<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $casts = [
        'is_approved' => 'boolean',
        'contract_id' => 'integer',
    ];

    public function getMinSalaryAttribute($value)
    {
        return $value / 100;
    }

    public function getMaxSalaryAttribute($value)
    {
        return $value / 100;
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->when($search, function($query) use($search) {
            $query
                ->where('position', 'LIKE', '%' . $search . '%')
                ->orWhere('title', 'LIKE', '%' . $search . '%')
                ->orWhere('location', 'LIKE', '%' . $search . '%');
        });
    }

    public function scopeWithSort($query, $sort)
    {
        return $query->when($sort, function($query) use($sort) {
            switch ($sort) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        });
    }

    public function scopeWithContractType($query, $contract)
    {
        return $query->when($contract, function($query) use($contract) {
            $ids = explode(',', $contract);
            $query
                ->whereIn('contract_id', $ids);
        });
    }

    public function scopeWithWorkingDay($query, $working_day)
    {
        return $query->when($working_day, function($query) use($working_day) {
            $query->where('working_day', $working_day);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
