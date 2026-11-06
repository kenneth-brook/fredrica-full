<?php

namespace Module\Forms\Models\Repositories;

use Module\Forms\Models\FormsIp;

class FormsIpRepository {

    public static function validFloodControl($form_id, $ip, $flood_control_time) {

        $model = FormsIp::where('form_id', '=', $form_id)
                ->where('ip', '=', $ip)
                ->first();

        $valid = TRUE;

        if (!empty($model)) {            

            $spamTime = \Carbon\Carbon::now()->subSeconds($flood_control_time)->timestamp;

            if ($model->updated_at->timestamp >= $spamTime) {
                //is spam
                $valid = FALSE;
            } else {
                //not spam
                $valid = TRUE;
                $model->touch();
            }
            
        } else {
            $data['ip'] = $ip;
            $data['form_id'] = $form_id;
            $model = FormsIp::create($data);

            $valid = TRUE;
        }
        return $valid;
    }

}
