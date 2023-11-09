<?php

namespace App\Controller;

use App\Model\AbstractModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    public function index(Request $request, AbstractModel $model): JsonResponse
    {
        if ($request->getMethod() === 'POST') {
            $data = json_decode($request->getContent(), true);
            $model->addQuestion([
                'title' => $data['title'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse($model->getRecords());
    }
}