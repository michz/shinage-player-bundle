<?php

namespace mztx\ShinagePlayerBundle\Service;

use mztx\ShinagePlayerBundle\Entity\CurrentPresentation;
use mztx\ShinagePlayerBundle\Entity\HeartbeatAnswer;
use Symfony\Component\Routing\RouterInterface;

class LocalScheduler
{

    /** @var LocalPresentationProvider */
    private $localProvider;

    /** @var Heartbeat */
    private $heartbeat;

    /** @var Remote */
    private $remote;

    /** @var RouterInterface */
    private $router;

    /** @var bool */
    protected $enabledLocal = true;

    /** @var bool */
    protected $enabledRemote = true;

    /**
     * @param LocalPresentationProvider $localProvider
     * @param Heartbeat $heartbeat
     */
    public function __construct(
        LocalPresentationProvider $localProvider,
        Heartbeat $heartbeat,
        Remote $remote,
        RouterInterface $router
    ) {
        $this->localProvider = $localProvider;
        $this->heartbeat = $heartbeat;
        $this->remote = $remote;
        $this->router = $router;
    }

    /**
     * Checks if a usb stick is mounted and which presentation should be played.
     * @return CurrentPresentation
     */
    public function getCurrentPresentation()
    {
        // First check if we can play a local presentation
        // @TODO check if local presentations are enabled/disabled in config
        $localPresentations = $this->localProvider->getPresentationList();

        if (!empty($localPresentations)) {
            $current = $localPresentations[0];
            return $current;
        }

        // Now check if we can play a remote presentation
        // @TODO Check if remote presentations are enabled/disabled in config
        try {
            /** @var HeartbeatAnswer $heartbeatAnswer */
            $heartbeatAnswer = $this->heartbeat->beat();
            if (!empty($heartbeatAnswer->presentation)) {
                // Show remote presentation.
                $current = new CurrentPresentation();
                $current->lastModified = $heartbeatAnswer->last_modified;
                $current->url = $this->router->generate(
                    'shinage.player.presentation.remote', ['id' => $heartbeatAnswer->presentation]
                );
                return $current;
            }
        } catch (\Exception $ex) {
            // @TODO Log the error somehow.
        }

        // No presentation found. Show splash.
        $current = new CurrentPresentation();
        $current->lastModified = 123;
        $current->url = $this->router->generate('shinage.player.splash');

        return $current;
    }
}
