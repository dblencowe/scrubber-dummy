<?php

namespace Dblencowe\ScrubberDummy;
use Illuminate\Database\MySqlConnection;

class Scrubber
{

    public function scrub(MySqlConnection $databaseHandler, $databaseName)
    {
        $databaseHandler->setDatabaseName($databaseName);
        return $databaseHandler->select('SELECT * FROM poll');
    }
}
