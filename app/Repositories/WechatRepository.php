<?php

namespace App\Repositories;

use App\Http\Model\WexingConcern;

class WechatRepository extends Base
{
    public function subscribe($data)
    {
        $record = WexingConcern::where("open_id", $data["open_id"])->first();
        if ($record) {
            return $record->update(array_merge($data, [
                "is_concern" => 1,
            ]));
        } else {
            return WexingConcern::create($data);
        }
    }

    public function unSubscribe($openId)
    {
        $record = WexingConcern::where("open_id", $openId)->first();
        if ($record) {
            return $record->update([
                "is_concern" => 0,
                "cancel_time" => date("Y-m-d H:i:s")
            ]);
        }

        return true;
    }

}