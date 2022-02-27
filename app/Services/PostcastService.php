<?php
namespace App\Services;

use App\Models\Genre;
use App\Models\Postcast;
use App\Models\PostcastGenre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostcastService{
    public function savePostcastToDB($crawled_postcasts, $genreId)
    {
        DB::beginTransaction();
        try {
            $postcast_genres = [];
            $postcasts = [];
            $fillable = (new Postcast())->fillable;
            foreach ($crawled_postcasts as $postcast) {
                //format some fields
                $postcast = (array) $postcast;
                $postcast['release_date'] = date('Y-m-d h:i:s', strtotime($postcast['release_date']));
                $postcast['id'] = $postcast['pid'];
                $postcast['tags'] = implode(',', $postcast['tags']);
                $postcast['keywords'] = implode(',', $postcast['keywords']);

                //get postcast genre
                foreach ($postcast['genres'] as $genre) {
                    $postcast_genres[] = ['postcast_id' => $postcast['pid'], 'genre_id' => $genre];
                }

                //remove unnecessary fields
                foreach ($postcast as $key => $value) {
                    if (!in_array($key, $fillable)) {
                        unset($postcast[$key]);
                    }
                }

                //$postcast['created_at'] = date('Y-m-d H:i:s');
                //$postcast['updated_at'] = date('Y-m-d H:i:s');
                $postcasts[] = $postcast;
            }

            //store data to postcasts and postcast_genres table
            DB::table('postcasts')->insertOrIgnore($postcasts);
            DB::table('postcast_genres')->insertOrIgnore($postcast_genres);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function listPostCasts($request, $listFields)
    {
        $query = Postcast::with('genres')->select($listFields)
            ->filter($request)
            ->releaseDate($request);

        //if you are not using mysql, you may need to use this group by
        //$query->groupBy('postcasts.id');

        return $query->paginate($request->limit ?? env('DEFAULT_PAGE_SIZE'));
    }
}
