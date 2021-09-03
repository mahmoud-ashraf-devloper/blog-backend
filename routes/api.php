<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// ============== Normal User Route ==============
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

// All articles
Route::get('/articles',[ArticleController::class, 'index']);

// all authors
Route::get('/authors', [AuthorController::class, 'index']);

// all categories
Route::get('/categories', [CategoryController::class, 'index']);

// show category
Route::get('/categories/{category}', [CategoryController::class, 'show'])->missing(function(){
    return ArticleController::handleNotFound('Category');
});

// one Article
Route::get('/articles/{article}',[ArticleController::class, 'show'])->missing(function(){
    return ArticleController::handleNotFound('Article');
});


// get Authour with his articles
Route::get('/authors/{author}', [AuthorController::class, 'show'])->missing(function(){
    return AuthorController::handleNotFound('Author');
});

// ================ Authenticated User =====================
Route::group(['middleware'=>['auth:sanctum']],function (){
    //get user role and permisstions
    Route::get('/role-permissions', function (){
        return response()->json([
            'role' => auth()->user()->getRoleNames(),
            'permissions' => auth()->user()->getAllPermissions()->pluck('name'),
        ]);
    });

    //logout
    Route::post('/logout',[AuthController::class, 'logout']);
    // add a comment

    // delete comment

});

// ============== End Of Normal User Route =======

// ============== Normal Author Route ============
Route::group(['middleware'=>['auth:sanctum', 'role:Author|Admin']], function(){

        // create article
        Route::post('/articles/store',[ArticleController::class,'store']);

        // update Article
        Route::post('/articles/{article}/update',[ArticleController::class, 'update'])->missing(function(){
            return ArticleController::handleNotFound('Article');
        });
});
// ============== End Of Author Route ============

// =============== Admin Route ===================
Route::group(['middleware'=>['auth:sanctum', 'role:Admin']], function(){

    // create an author
    Route::post('/authors/store', [AuthorController::class, 'store']);

    // update an author
    Route::post('/authors/{author}/update', [AuthorController::class, 'update'])->missing(function(){
        return AuthorController::handleNotFound('Author');
    });

    // delete an author
    Route::post('/authors/{author}/delete', [AuthorController::class, 'delete'])->missing(function(){
        return AuthorController::handleNotFound('Author');
    });

    // delete an article
    Route::post('articles/{article}/delete',[ArticleController::class, 'delete'])->missing(function(){
        return ArticleController::handleNotFound('Article');
    });

    // create category
    Route::post('/categories/store', [CategoryController::class, 'store']);

    // update category
    Route::post('/categories/{category}/update', [CategoryController::class, 'update'])->missing(function(){
        return ArticleController::handleNotFound('Category');
    });


    // delete an category
    Route::post('/categories/{category}/delete', [CategoryController::class, 'delete'])->missing(function(){
        return ArticleController::handleNotFound('Category');
    });
});


