<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\UMsg;
use Illuminate\Http\Request;

/**
 * Class UMsgController 短信
 * @package App\Http\Controllers\Api
 */
class UMsgController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth:api")->except(['search', 'childrenAgent']);
    }

    public function list(Request $request)
    {
        $per_page = $request->input('size', self::PAGE_SIZE);
        $keyword = $request->input('keyword');

        $query = UMsg::orderBy('id', 'desc')->with('relation', 'client');
        if ($keyword) {
            $query->where('cellphone', 'like', "%$keyword%");
        }
        $data = $query->paginate($per_page);
        return self::jsonReturn($data);
    }


}