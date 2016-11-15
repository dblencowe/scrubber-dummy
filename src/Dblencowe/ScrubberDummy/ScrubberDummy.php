<?php

namespace Dblencowe\ScrubberDummy;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

class Scrubber implements PluginInterface, EventSubscriberInterface
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
        echo 'Running ScrubberDummy...';
        $cachePath = defined('CACHE_PATH') ? CACHE_PATH : getcwd() . '/.dataScrubber.cache';
        if (!is_file($cachePath)) {
            throw new \Exception("Cache file $cachePath not found");
        }

        file_put_contents($cachePath, json_encode($this->driverInfo), FILE_APPEND | LOCK_EX);
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
