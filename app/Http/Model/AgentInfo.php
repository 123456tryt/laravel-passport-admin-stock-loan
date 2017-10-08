<?php

namespace App\Http\Model;


class AgentInfo extends Base
{
    protected $table = "a_agent_extra_info";
    protected $fillable = [
        'platform_name',
        'agent_web_domain',
        'agent_phone_domain',
        'province',
        'city',
        'address',
        'agent_service_phone',
        'service_email',
        'service_qq',
        'qq_group',
        'serivce_time',
        'contactor',
        'bank_name',
        'phone',
        'bank_card_no',
        'owner_name',
        'website_record_no',
        'copyright',
        'index_title',
        'page_desc',
        'page_key_word',
    ];


}
