<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Models\Grade;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Controller\Controller as BaseController;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;
use App\Middleware\AdminMiddleware;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * Class GradesController
 * @Controller(prefix="/api/admin")
 * @package App\Controller\Api\Admin
 * @Middleware(AdminMiddleware::class)
 */
class GradesController extends BaseController
{
    /**
     * @GetMapping(path="grades")
     * @return ResponseInterface
     */
    public function index()
    {
        $grades = Grade::query()->searchSelect()->latest()->get();

        return $this->response(compact('grades'));
    }

    /**
     * @PostMapping(path="grades")
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function store(RequestInterface $request)
    {
        $gradeModel = make(Grade::class);

        $grade = $gradeModel->where($request->inputs(['name']))->first();

        if (!empty($grade)) {
            return $this->response([], 409);
        }

        $grade = $gradeModel->fill($request->all());

        $grade->save();

        return $this->response([], 201);
    }


    /**
     * @GetMapping(path="grades/{grade:\d+}")
     * @param int $grade
     * @return \App\Exception\HttpException|ResponseInterface
     */
    public function show(int $grade)
    {
        $grade = Grade::query()->searchSelect()->findOrFail($grade);


        return $this->response(compact($grade));
    }

    /**
     * @PutMapping(path="grades/{grade:\d+}")
     * @param int $grade
     * @param RequestInterface $request
     * @return \App\Exception\HttpException|ResponseInterface
     */
    public function update(int $grade, RequestInterface $request)
    {
        $grade = Grade::query()->findOrFail($grade);

        $validate = Grade::query()
            ->validateName($grade->id)
            ->searchName($request->input('name'))
            ->first();

        if (!empty($validate)) {
            return $this->error('名称重复', 409);
        }

        $grade->name = $request->input('name');
        $grade->save();

        return $this->response([], 204);
    }
}
