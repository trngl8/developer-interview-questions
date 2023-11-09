<?php

namespace App\Controller;

use App\Model\AbstractModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    public function index(Request $request, AbstractModel $model): JsonResponse
    {
        return new JsonResponse($model->getRecords());
    }
}