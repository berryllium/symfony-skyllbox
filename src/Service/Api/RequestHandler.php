<?php

namespace App\Service\Api;


use App\Form\Model\ArticleFormModel;
use Symfony\Component\HttpFoundation\Request;

class RequestHandler
{
    public function prepareArticleFormModel(Request $request) {
        $params = $request->toArray();
        $model = new ArticleFormModel();

        $model->title = $params['title'] ?? '';
        $model->subject = $params['subject'];
        $model->sizeFrom = $params['sizeFrom'] ?? 1;
        $model->sizeTo = $params['sizeTo'] ?? 3;
        $model->images = $params['images'] ?? [];

        foreach ($params['words'] as $k => $word) {
            $model->words[$k] = $word['word'];
            $model->wordsCount[$k] = $word['count'];
        }

        foreach ($params['keyword'] as $k => $keyword) {
            $key = 'keyword' . $k;
            $model->$key = $keyword;
        }

        return $model;
    }
}