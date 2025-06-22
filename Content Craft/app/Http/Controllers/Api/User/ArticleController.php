<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ArticleCreateRequest;
use App\Http\Requests\Api\User\ArticleUpdateRequest;
use App\Http\Resources\Article\AllArticleResource;
use App\Http\Resources\Article\ArticleResource;
use App\Repositories\Repositories\ArticleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function __construct(public ArticleRepository $articleRepository)
    {
    }


    // Add Article
    public function store(ArticleCreateRequest $request)
    {
        try {
            $articleData = $this->articleRepository->create($request->all());
            if ($articleData == null) {
                return response()->json(['response' => ['success' => false, 'message' => 'Insufficient Articles. First Purchase Articles.']], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            return response()->json(['response' => ['success' => true, 'data' => new ArticleResource($articleData)]], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // show auth user Articles
    public function show()
    {
        try {
            $articleData = $this->articleRepository->ownArticles();
            return response()->json(['response' => ['success' => true, 'data' => AllArticleResource::collection($articleData)]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // fetchArticle Article
    public function fetchArticle($articleId)
    {
        try {
            $articleData = $this->articleRepository->find($articleId);
            return response()->json(['response' => ['success' => true, 'data' => new ArticleResource($articleData)]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // edit Article
    public function edit($articleId)
    {
        try {
            $articleData = $this->articleRepository->find($articleId);
            return response()->json(['response' => ['success' => true, 'data' => new ArticleResource($articleData)]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // update Article
    public function update(ArticleUpdateRequest $articleUpdateRequest, $articleId)
    {
        try {
            $this->articleRepository->update($articleId, $articleUpdateRequest->all());
            return response()->json(['response' => ['success' => true, 'message' => 'Article has been Updated Successfully...']], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // Delete Article
    public function destroy($articleId)
    {
        try {
            $this->articleRepository->delete($articleId);
            return response()->json(['response' => ['success' => true, 'message' => "Artical has been Deleted Successfully..."]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // get all Article
    public function getAll()
    {
        try {
            $articles =  $this->articleRepository->all();
            return response()->json(['response' => ['success' => true, 'data' => AllArticleResource::collection($articles)]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
