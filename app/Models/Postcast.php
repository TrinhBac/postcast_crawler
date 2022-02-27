<?php

namespace App\Models;

use App\Http\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Postcast extends Model
{
    use ModelHelperTrait;

    public $incrementing = false;
    protected $casts = [
//        'tags' => 'array',
//        'keywords' => 'array',
    ];

    public $fillable = ['id', 'feedUrl', 'short_url', 'https_cover', 'keywords', 'copyright',
                        'long_description', 'downloads', 'title', 'itunes_cover', 'author',
                        'type', 'email', 'tags', 'link', 'key', 'description', 'cover',
                        'raw_description', 'release_date'];

    public $responseFields = ['id', 'feedUrl', 'short_url', 'https_cover', 'keywords', 'copyright',
                        'long_description', 'downloads', 'title', 'itunes_cover', 'author',
                        'type', 'email', 'tags', 'link', 'key', 'description', 'cover',
                        'raw_description', 'release_date'];

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genre', 'postcast_genres', 'postcast_id', 'genre_id')
            ->select(['genres.id', 'name', 'image']);
    }

    //which fields use want to search by operation '='; ex: gender = 1, country = 24, ...
    public $filterEqual = [
        'type', 'email', 'key',
//        'category' => 'genre'
    ];

    //search for genre_id which genre is a string of id, separate by comma
    public function scopeGenres($query, $value)
    {
        $query->whereHas('genres', function ($query) use ($value) {
            $query->whereIn('genre_id', explode(',', $value));
        });

        return $query;
    }

    public function scopeTitle($query, $value)
    {
        return $query->whereRaw("(LOWER(title) LIKE ?)")->setBindings(['%' . strtolower($value) . '%']);
    }

    public function scopeLongDescription($query, $value)
    {
        return $query->whereRaw("(LOWER(long_description) LIKE ?)")->setBindings(['%' . strtolower($value) . '%']);
    }

    public function scopeAuthor($query, $value)
    {
        return $query->whereRaw("(LOWER(author) LIKE ?)")->setBindings(['%' . strtolower($value) . '%']);
    }

    public function scopeReleaseDate($query, $request)
    {
        if ($request->start_date && $request->end_date) {
            $from = date($request->start_date);
            $to = date($request->end_date);
            return $query->whereBetween('release_date', [$from, $to]);
        }

        return $query;
    }

    public function scopeKeywords($query, $value)
    {
        //convert keywords from string to array and write raw query
        $keywords = explode(',', strtolower($value));
        $sql = '';
        foreach ($keywords as $index => &$keyword) {
            $keyword = "%$keyword%";
            if ($index == 0) {
                $sql .= "((LOWER(keywords) LIKE ?)";
            } else {
                $sql .= " OR (LOWER(keywords) LIKE ?)";
            }
        }
        $sql .= ")";

        return $query->whereRaw($sql)->setBindings($keywords);
    }

}
