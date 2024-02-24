<?php 

use Firebase\JWT\JWK;

class ScheduleController
{

    public function addNewSchedule($school_id, $name, $place, $class_info, $schedule, $user_token)
    {
        Model::loadModel("Schedule/schedule");

        ScheduleModel::addNewSchedule($school_id, $name, $place, $class_info, $schedule, $user_token);
    }
}
