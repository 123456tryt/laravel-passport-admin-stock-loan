<?php

namespace App\Repositories;

use App\Http\Model\AgentExtraInfo;

class OthersRepository extends Base
{
    /**
     * 获取首页数据
     * @param $id
     * @return bool
     */
    public function getIndexData($id)
    {
        $id = $id ?: getDefaultAgent()->id;
        $ret = AgentExtraInfo::where('id', $id)->get([
            "id", "platform_name", "web_domain", "mobile_domain", "province", "city", "address",
            "service_time", "service_phone", "service_email", "service_qq", "qq_group", "website_record_no",
            "copyright", "seo_title", "seo_description", "seo_keyword", "cust_qr"
        ]);
        return $ret ? $ret->toArray() : false;
    }
}