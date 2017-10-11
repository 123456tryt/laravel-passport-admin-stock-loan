<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OthersRepository;

class OthersController extends Controller
{
    private $other = null;

    public function __construct(OthersRepository $other)
    {
        $this->other = $other;
    }

    /**
     * 获取首页数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndexData(Request $request)
    {
        $ret = $this->other->getIndexData((int)$request->get("id"));
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "data error");
    }
}