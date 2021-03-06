<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'is_approved' => 'boolean',
        'remote_working' => 'boolean',
        'contract_id' => 'integer',
    ];

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

    public function scopeWithRemoteWorking($query, $remote_working)
    {
        return $query->when($remote_working != '', function($query) use($remote_working) {
            $query->where('remote_working', (boolean)$remote_working);
        });
    }

    public function scopeWithTags($query, $tags)
    {
        return $query->when($tags, function($query) use($tags) {
            $ids = explode(',', $tags);
            
            $query->whereHas('tags', function($query) use($ids) {
                $query->whereIn('tags.id', $ids);
            });
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
