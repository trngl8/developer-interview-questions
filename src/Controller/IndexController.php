<?php

namespace App\Controller;

use App\Database\Query;
use App\Form\NewQuestionType;
use App\Model;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class IndexController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(Request $request, Model $model)
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();
        $form = $formFactory->create(NewQuestionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $request->getMethod() === 'POST') {

            $data = $form->getData();
            if (!$data->title) {
                $_SESSION['message'] = [
                    'title' => 'Question title is required',
                    'type' => 'error',
                ];
                return new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
            }

            $model->addQuestion([
                'title' => $data->title,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $_SESSION['message'] = [
                'title' => 'Question added',
                'type' => 'success',
            ];
            return new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
        }

        $records = $model->getRecords();
        $content = $this->twig->render('index.html.twig', [
            'questions' => $records,
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }
}
