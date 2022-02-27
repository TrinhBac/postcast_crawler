<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListPostcastRequest;
use App\Models\Genre;
use App\Models\Postcast;
use App\Repositories\ApiEloquentRepository;
use App\Services\PostcastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostcastController extends Controller
{
    protected $model;
    protected $service;
    protected $repository;

    public function __construct(Postcast $postcast, PostcastService $postcastService)
    {
        $this->model = $postcast;
        $this->service = $postcastService;
        $this->repository = new ApiEloquentRepository($postcast);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListPostcastRequest $request)
    {
        $genres = $this->service->listPostCasts($request, $this->model->responseFields);

        return response()->json([
            'success' => true,
            'data' => $genres,
            'message' => 'Get data successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $genreIds = Genre::pluck('id')->toArray();
        //get all postcasts of each genres and push them into an array
        foreach ($genreIds as $genreId) {
            $response = Http::get(env('POSTCAST_API'), [
                'genre' => $genreId,
                'skip' => 0,
                'limit' => 1000,
            ]);

            //check if request is success then process data to save
            if ($response->status() === 200 && isset($response->json()['data'])) {
                $crawledPostcasts = [];
                foreach ($response->json()['data'] as $postcast) {
                    $crawledPostcasts[] = $postcast;
                }

                $this->service->savePostcastToDB($crawledPostcasts, $genreId);
            }
        }

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Load data from server to DB successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Postcast  $postcast
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

}
