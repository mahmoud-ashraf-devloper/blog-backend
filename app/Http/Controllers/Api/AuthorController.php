<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\ArticleResource;
use App\Models\Author;
use App\Traits\imageUploade;



class AuthorController extends Controller
{
    use imageUploade;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->sendResponse(AuthorResource::collection(Author::all()),'All Authors retrieved succssfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ValidateAuthorRequest $request)
    {
        // here the image is required
        $request->validate([
            'photo' => 'required',
        ]);

        $Storedresponse = $this->uploadeImage($request, 'photo', 'authors');
        if($Storedresponse['success']){
            $formData = array_merge($request->except(['photo']), ['photo'=> $Storedresponse['imageUrl']]);
            $created = Author::create($formData);
            return $this->sendResponse($created, 'Author Created Successfully');
        }
        return $this->sendError('Something Went Wrong Please Try a gain later Code \'1\'');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Author $author)
    {
        $author = $author->load(['articles']);
        $response = [
            'author' => new AuthorResource($author),
            'articles' => ArticleResource::collection($author->articles->load(['category'])),
        ];
        return $this->sendResponse($response ,'All Author Articles retrieved succssfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ValidateAuthorRequest $request, Author $author)
    {
        if($request->hasFile('photo')){
            $storedResponse = $this->uploadeImage($request, 'photo', 'authors');
            if($storedResponse['success']){
                $formData = array_merge($request->except(['photo']), ['photo'=> $storedResponse['imageUrl']]);
                if($author->update($formData)){
                    return $this->sendResponse($author, 'Author updated Successfully');
                }
                return $this->sendError('Something Went Wrong Please Try a gain later Code \'1\'');
            }
            return $this->sendError('Something Went Wrong Please Try a gain later Code \'2\'');
        }

        if ($author->update($request->except(['photo']))){
            return $this->sendResponse($author, 'Author updated Successfully');
        }
        return $this->sendError('Something Went Wrong Please Try a gain later Code \'3\'');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Author $author)
    {
        $deleted  = $author;
        if($author->delete()){
            return $this->sendResponse($deleted, 'Author Deleted Successfully');
        }
        return $this->sendResponse($deleted, 'Something Went Wrong Try Again later');
    }
}
