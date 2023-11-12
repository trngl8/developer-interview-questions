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
            $result = $model->addQuestion([
                'title' => $data['title'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return new JsonResponse(['status' => 'success', 'id' => $result['id']]);
        }

        return new JsonResponse($model->getRecords());
    }

    public function delete(Request $request, AbstractModel $model, $id): JsonResponse
    {
        $model->remove($id);
        return new JsonResponse(['status' => 'success']);
    }
}
