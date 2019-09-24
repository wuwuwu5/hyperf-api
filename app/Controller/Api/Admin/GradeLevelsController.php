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
}
