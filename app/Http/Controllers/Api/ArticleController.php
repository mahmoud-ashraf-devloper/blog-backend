<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Traits\imageUploade;


class ArticleController extends Controller
{

    use imageUploade;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $articles = new ArticleCollection(Article::with(['author','category'])->paginate(5));
        return $this->sendResponse($articles,'data retreved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ValidateArticleRequest $request)
    {

        $storeResponse  = $this->uploadeImage($request, 'photo', 'articles');

        // in the store photo is required
        $request->validate(['photo' => 'required']);

        if($storeResponse['success']){
            $articleData = array_merge($request->except(['photo']), ['photo' => $storeResponse['imageUrl']]);
            $created = Article::create($articleData);
            return $this->sendResponse($created, 'Article Created Succssfully');
        }
        return $this->sendError($storeResponse['message']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Article $article)
    {
        $article = new ArticleResource($article->load(['comments','category','author']));
        return $this->sendResponse($article, 'Article Retreved Successfuly');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ValidateArticleRequest $request, Article $article)
    {
        /*
            * Return sample of uploadeImage
            [
                'success' => false,
                'imageUrl' => null,
                'message' => null,
            ]
        */
        if($request->hasFile('photo')){
            $storeResponse  = $this->uploadeImage($request, 'photo', 'articles');
            if($storeResponse['success']){
                $articleData = array_merge($request->except(['photo']), ['photo' => $storeResponse['imageUrl']]);
                if($article->update($articleData)){
                    return $this->sendResponse($article, 'Article updated Succssfully');
                }
                return $this->sendError('Something Went Wrong Please Try Again later');
            }
        }

        $articleData = $request->except(['photo']);
        if($article->update($articleData)){
            return $this->sendResponse($article, 'Article updated Succssfully');
        }
        return $this->sendError('Something Went Wrong Please Try Again later');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Article $article)
    {
        $deleted = $article;
        if($article->delete()){
            return $this->sendResponse($deleted, 'Article Deleted Successfully');
        }
        return $this->sendError('Something Went Wrong Try Agailn later');
    }
}
