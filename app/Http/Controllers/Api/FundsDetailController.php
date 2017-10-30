<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FundsDetailRepository;

class FundsDetailController extends Controller
{
    private $fundsDetail = null;

    public function __construct(FundsDetailRepository $fundsDetail)
    {
        $this->middleware(["auth:api", "App\Http\Middleware\UserForbidden"]);

        $this->fundsDetail = $fundsDetail;
    }

    /**
     * 资金明细列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFundsDetails(Request $request)
    {
        $ret = $this->fundsDetail->getFundsDetails($request->user(), $request->get("type"));
        return $ret !== false ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "data error");
    }
}