<?php

namespace App\Http\Controllers\User;

use App\DataTables\ArticlesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ArticleCreateRequest;
use App\Http\Requests\User\ArticleUpdateRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Repositories\Repositories\ArticleRepository;


class ArticleController extends Controller
{

    public function __construct(public ArticleRepository $articleRepository)
    {
    }
    // index page of articles
    public function index(ArticlesDataTable $articlesDataTable)
    {
        try {
            return $articlesDataTable->render('article.index');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // show create page
    public function create()
    {
        try {
            return view('article.create');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Add Article
    public function store(ArticleCreateRequest $request)
    {
        try {
            $articleData = $this->articleRepository->create($request->all());
            if ($articleData == null) {
                return response(['success' => false, 'message'=> "Insufficient Articles. First Purchase Articles"],422);
            }
            return response(['success' => true, 'message' => "Article Added Successfully..."]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // show Article
    public function show($articleId)
    {
        try {
            $articleData = $this->articleRepository->find($articleId);
            return response(['success' => true, 'data' => new ArticleResource($articleData)]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // edit Article
    public function edit($articleId)
    {
        try {
            $articleData = $this->articleRepository->find($articleId);
            return response(['success' => true, 'data' => new ArticleResource($articleData)]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // update Article
    public function update(ArticleUpdateRequest $articleUpdateRequest, $articleId)
    {
        try {
            $this->articleRepository->update($articleId, $articleUpdateRequest->all());
            return response(['success' => true, 'message' => 'Article has been Updated Successfully...']);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // Delete Article
    public function destroy($articleId)
    {
        try {
            $this->articleRepository->delete($articleId);
            return redirect()->route('article.index')->with(['message' => 'Article has been Deleted Successfully...']);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
