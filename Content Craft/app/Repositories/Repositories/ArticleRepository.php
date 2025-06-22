<?php

namespace App\Repositories\Repositories;

use App\Models\Article;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Subscriptions;
use App\Repositories\Interfaces\ArticleInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class ArticleRepository implements ArticleInterface
{

    // get all users article
    public function all()
    {
        try {
            return Article::whereNot('userId', auth()->id())->get();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // get own article
    public function ownArticles()
    {
        try {
            return Article::where('userId', auth()->id())->get();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function find($articleId)
    {
        try {
            $articleData = Article::find($articleId);
            return $articleData;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // add article
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $checkArticles = Subscriptions::where('userId', auth()->user()->id)->first();
            if ($checkArticles == null) {
                return null;
            } elseif ($checkArticles->articles == 0) {
                return null;
            } else {
                $article = Article::create([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'userId' => auth()->id(),
                ]);
                $checkArticles->update([
                    'articles' => $checkArticles->articles - 1,
                ]);
                DB::commit();
                return $article;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    // update article
    public function update($articleId, $data)
    {
        try {
            DB::beginTransaction();
            $article = Article::find($articleId)->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'userId' => auth()->id(),
            ]);
            DB::commit();
            return $article;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    // delete article
    public function delete($article)
    {
        try {
            DB::beginTransaction();
            $articleData = Article::find($article)->delete();
            DB::commit();
            return $articleData;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // like article
    public function like($articleId)
    {
        try {
            DB::beginTransaction();
            $articleData = Article::find($articleId);
            $user = $articleData->articlesToUser;
            Like::create([
                'articleId' => $articleId,
                'userId' => auth()->user()->id
            ]);
            Notification::create([
                'senderId' => auth()->user()->id,
                'receiverId' => $user->id
            ]);
            $filePath = public_path('../firebase_config.json');
            $firebase = (new Factory)->withServiceAccount($filePath);
            $messaging = $firebase->createMessaging();
            $deviceToken = $user->uuid;
            $message = CloudMessage::new()
                ->withNotification(['title' => 'Like Article', 'body' => '' . auth()->user()->firstName . ' likes your article']);
            $messaging->send($message, $deviceToken);
            DB::commit();
            return $articleData;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        } 
    }
}