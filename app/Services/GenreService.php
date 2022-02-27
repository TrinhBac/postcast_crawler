<?php
namespace App\Services;

use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenreService{
    public function saveGenreToDB($responseData)
    {
        DB::beginTransaction();
        try {
            foreach ($responseData as $genre) {
                Genre::updateOrCreate(
                    ['id' => $genre['id']],
                    [
                        'image' => $genre['image'],
                        'name' => $genre['name'],
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'Load data from server to DB successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(), //'Error while getting data from server.'
            ]);
        }
    }

    public function listGenresByName($name, $listFields)
    {
        if ($name) {
            $name = Str::lower($name);
            return Genre::whereRaw("(LOWER(name) LIKE ?)")->setBindings(["%$name%"])->get($listFields);
        }

        return Genre::all($listFields);
    }
}
