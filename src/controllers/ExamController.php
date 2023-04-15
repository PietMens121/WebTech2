<?php

namespace src\controllers;


use App\Database\Auth;
use App\Database\Builder\QueryBuilder;
use App\Http\Response;
use App\Templating\Render;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use src\models\Exam;
use src\models\User;

class ExamController extends Controller
{
    public function index(): ResponseInterface
    {
        if(request()->getQueryParams()) {
            if (request()->getQueryParams()['filter'] === 'true') {
                $exams = Auth::user()->ExamAdmin();
            }
        } else
        {
            $exams = new Exam();
            $exams = $exams->all();
        }

        return Render::view('exams/exams.html', [
            'exams' => $exams
        ]);
    }

    public function show($id): ResponseInterface
    {
        $exam = new Exam();
        $exam = $exam->find($id);

        if (!isset($exam)) {
            abort(404);
        }

        $users = $exam->withPivot(User::class);

        return Render::view('exams/exam.html', [
            'id' => $id,
            'exam' => $exam,
            'users' => $users
        ]);
    }

    public function attach($id): ResponseInterface
    {
        $exam = new Exam();
        $exam = $exam->find($id);

        $error = $exam->attach(User::class, '1');

        if (!$error) {
            abort(404);
        }

        return Response::redirect('/exams/' . $id);
    }

    public function updateGrade($id, $user_id): ResponseInterface
    {

        dd($user_id);

        return Response::redirect('/exam/' . $id);
    }
}