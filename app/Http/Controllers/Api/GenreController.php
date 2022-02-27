<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Repositories\ApiEloquentRepository;
use App\Services\GenreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GenreController extends Controller
{
    protected $model;
    protected $service;
    protected $repository;

    public function __construct(Genre $genre, GenreService $genreService)
    {
        $this->model = $genre;
        $this->service = $genreService;
        $this->repository = new ApiEloquentRepository($genre);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $genres = $this->service->listGenresByName($request->name, $this->model->responseFields);

        return response()->json([
            'success' => true,
            'data' => $genres,
            'message' => 'Get data successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse | void
     */
    public function store(Request $request)
    {
        $response = Http::get(env('GENRE_API'));
        if ($response->status() !== 200) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Error while getting data from server.'
            ], 500);
        }

        $responseData = $response->json()['data'];
        return $this->service->saveGenreToDB($responseData);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Genre $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }
}
