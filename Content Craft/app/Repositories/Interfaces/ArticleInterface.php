<?php

namespace App\Repositories\Interfaces;

interface ArticleInterface
{
    public function all();
    public function ownArticles();
    public function find($articleId);
    public function create($data);
    public function update($articleId, $data);
    public function delete($articleId);
    public function like($articleId);
}