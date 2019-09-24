<?php

declare(strict_types=1);

namespace App\Aspect;

use App\Models\Grade;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;

/**
 * @Aspect
 */
class GradeAspect extends AbstractAspect
{
    /**
     * @Inject
     * @var RequestInterface
     */
    public $request;


    /**
     * 切入的类
     *
     * @var array
     */
    public $classes = [
        'App\Controller\Api\Admin\GradeLevelsController',
    ];


    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $gradeId = data_get($proceedingJoinPoint->getArguments(), 0);

        $grade = Grade::query()->findOrFail($gradeId);

        Context::set('grade', $grade);

        return $proceedingJoinPoint->process();
    }
}
