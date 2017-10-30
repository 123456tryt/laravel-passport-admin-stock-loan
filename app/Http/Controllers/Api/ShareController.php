<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ShareRepository;

class ShareController extends Controller
{
    private $share = null;

    public function __construct(ShareRepository $share)
    {
        $this->middleware(["auth:api", "App\Http\Middleware\UserForbidden"]);
        $this->share = $share;
    }

    /**
     * 获取推广统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShareCount(Request $request)
    {
        $ret = $this->share->getShareCount($request->user());
        return $ret !== false ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "获取失败");
    }

    /**
     * 获取推广用户记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPromotionUsers(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "level" => "required|integer|between:1,2",
        ], [
            "level.required" => "级别不能为空",
            "level.between" => "级别应该在1-2之间",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->share->getPromotionUsers($request->user(), $request->get("level"));
        return $ret !== false ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "获取失败");
    }

    /**
     * 获取推广收益
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPromotionPercentages(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "level" => "required|integer|between:1,2",
        ], [
            "level.required" => "级别不能为空",
            "level.between" => "级别应该在1-2之间",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->share->getPromotionPercentages($request->user(), $request->get("level"));
        return $ret !== false ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "获取失败");
    }
}