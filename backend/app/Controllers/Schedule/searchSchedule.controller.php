<?php

use Firebase\JWT\JWT;

class SearchScheduleController
{

    public function searchEvents($school_id)
    {
        Model::loadModel("Schedule/searchSchedule");

        SearchScheduleModel::searchEvents($school_id);
    }
}
