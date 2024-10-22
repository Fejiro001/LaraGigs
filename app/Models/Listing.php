<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    // protected $fillable = ['title', 'company', 'location', 'website', 'email', 'description', 'tags'];

    public function scopeFilter($query, array $filters)
    {
        // Filter the listings with the tags when clicked
        if ($filters['tag'] ?? false) {
            // Same as: SELECT * FROM listings WHERE tags LIKE '%tag%';
            $query
                ->where('tags', 'like', '%' . request('tag') . '%');
        };

        // Filter listings with the search bar using title, description or tags
        if ($filters['search'] ?? false) {
            // Same as: SELECT * FROM listings WHERE tags LIKE '%tag%';
            $query
                ->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        };
    }

    // Relationship to user
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
