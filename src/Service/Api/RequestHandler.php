<?php

namespace App\Service\Api;


use App\Form\Model\ArticleFormModel;
use Symfony\Component\HttpFoundation\Request;

class RequestHandler
{
    public function prepareArticleFormModel(Request $request) {
        $model = new ArticleFormModel();
        foreach ($request->toArray() as $prop => $value) {
            if($prop == 'words') {
                foreach ($value as $k => $word) {
                    $model->words[$k] = $word['word'];
                    $model->wordsCount[$k] = $word['count'];
                }
            } elseif ($prop == 'keyword') {
                foreach ($value as $k => $keyword) {
                    $key = 'keyword' . $k;
                    $model->$key = $keyword;
                }
            } elseif (property_exists($model, $prop)) {
                $model->$prop = $value;
            }
        }
        if(!isset($model->sizeFrom)) {
            $model->sizeFrom = 1;
        }
        if(!isset($model->sizeTo)) {
            $model->sizeTo = 3;
        }
        return $model;
    }
}