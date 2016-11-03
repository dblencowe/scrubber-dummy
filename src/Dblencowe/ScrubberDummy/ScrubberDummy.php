<?php

namespace Dblencowe\ScrubberDummy;

use Composer\Script\ScriptEvents;

class Scrubber
{
    private $driverInfo = [
        'name' => 'Dummy Scrubber',
        'scrubber' => 'Dblencowe\\ScrubberDummy\\scrub',
    ];

    public function scrub()
    {
        echo 'Hello world!';
    }

    /**
     * Get the subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => [
                ['registerPlugin', 0]
            ],
            ScriptEvents::POST_UPDATE_CMD => [
                ['registerPlugin', 0]
            ],
        ];
    }

    public function registerPlugin()
    {
        $cachePath = defined('CACHE_PATH') ? CACHE_PATH : getcwd() . '/.dataScrubber.cache';
        if (!is_file($cachePath)) {
            throw new \Exception("Cache file $cachePath not found");
        }

        file_put_contents($cachePath, json_encode($this->driverInfo), FILE_APPEND | LOCK_EX);
    }

}
