<?php

namespace Dblencowe\ScrubberDummy;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Scrubber implements PluginInterface, EventSubscriberInterface
{
    private $driverInfo = [
        'name' => 'dummy-scrubber',
        'scrubber' => 'Dblencowe\\ScrubberDummy\\Scrubber\\scrub',
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

    public function registerPlugin(Event $event)
    {
        $cachePath = defined('CACHE_PATH') ? CACHE_PATH : getcwd() . '/.dataScrubber.cache';

        $drivers = [];
        if (file_exists($cachePath)) {
            $drivers = json_decode(file_get_contents($cachePath));
        }

        $drivers[$this->driverInfo['name']] = $this->driverInfo['scrubber'];

        file_put_contents($cachePath, json_encode($drivers), FILE_APPEND | LOCK_EX);
    }

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->package = $composer->getPackage();
        $this->io = $io;
        $this->projectRoot = getcwd();
    }
}
