<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Models\Grade;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use App\Controller\Controller as BaseController;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Utils\Context;

/**
 * Class GradeLevelsController
 * @Controller(prefix="/api/admin")
 * @package App\Controller\Api\Admin
 */
class GradeLevelsController extends BaseController
{
    /**
     * @param int $grade
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @GetMapping(path="grades/{grade:\d+}/levels")
     * @return ResponseInterface
     */
    public function index(int $grade, RequestInterface $request, ResponseInterface $response)
    {
        $gradeLevels = Context::get('grade')
            ->gradeLevels()
            ->select('id', 'grade_id', 'name')
            ->latest()
            ->get();

        return $this->response(compact('gradeLevels'));
    }

    /**
     * @param int $grade
     * @PostMapping(path="grades/{grade:\d+}/levels")
     * @param RequestInterface $request
     * @return \App\Exception\HttpException|ResponseInterface
     */
    public function store(int $grade, RequestInterface $request)
    {
        if (!$request->has('name')) {
            return $this->error('参数错误', 422);
        }

        $gradeLevel = Context::get('grade')->gradeLevels()->where($request->inputs(['name']))->first();

        if (!empty($gradeLevel)) {
            return $this->error('名称重复', 429);
        }

        Context::get('grade')->gradeLevels()->create($request->inputs(['name']));

        return $this->response([], 201);
    }


    /**
     * @param int $grade
     * @param int $level
     * @GetMapping(path="grades/{grade:\d+}/levels/{level:\d+}")
     * @return ResponseInterface'
     */
    public function show(int $grade, int $level)
    {
        $gradeLevel = Context::get('grade')->gradeLevels()->findOrFail($level);

        return $this->response(compact('gradeLevel'));
    }


    /**
     * @param int $grade
     * @param int $level
     * @param RequestInterface $request
     * @PutMapping(path="grades/{grade:\d+}/levels/{level:\d+}")
     * @return \App\Exception\HttpException|ResponseInterface
     */
    public function update(int $grade, int $level, RequestInterface $request)
    {
        $grade = Context::get('grade');

        $gradeLevel = $grade->gradeLevels()->findOrFail($level);


        $mark = $grade->gradeLevels()->where('id', '<>', $gradeLevel->id)->where($request->inputs(['name']))->first();

        if (!empty($mark)) {
            return $this->error('名称重复', 429);
        }

        $gradeLevel->name = $request->input('name');
        $gradeLevel->save();

        return $this->response([], 204);
    }
}
