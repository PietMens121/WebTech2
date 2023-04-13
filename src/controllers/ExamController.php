<?php

namespace src\controllers;


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
        $exams = new Exam();
        $exams = $exams->all();

        return Render::view('exams/exams.html', [
            'exams' => $exams
        ]);
    }

    public function show($id): ResponseInterface
    {
        $exam = new Exam();
        $exam = $exam->find($id);

        if (!isset($exam)) {
            Response::abort(404);
        }

        return Render::view('exams/exam.html', [
            'id' => $id,
            'exam' => $exam,
        ]);
    }

    public function attach($id): ResponseInterface
    {
        $exam = new Exam();
        $exam = $exam->find($id);


    }
}