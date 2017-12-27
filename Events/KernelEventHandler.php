<?php
/**
 * Created by PhpStorm.
 * User: michi
 * Date: 27.12.17
 * Time: 15:03
 */

namespace mztx\ShinagePlayerBundle\Events;

use mztx\ShinagePlayerBundle\Service\RuntimeConfiguration;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class KernelEventHandler
{
    /** @var RuntimeConfiguration */
    private $runtimeConfiguration;

    /**
     * KernelEventHandler constructor.
     * @param RuntimeConfiguration $runtimeConfiguration
     */
    public function __construct(RuntimeConfiguration $runtimeConfiguration)
    {
        $this->runtimeConfiguration = $runtimeConfiguration;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerCallable = $event->getController();
        if (!is_callable($controllerCallable)) {
            return;
        }

        $request = $event->getRequest();
        $screen = $request->headers->get('X-SCREEN');
        if ($screen !== null) {
            $this->runtimeConfiguration->setScreenGuid($screen);
        }

        $preview = $request->headers->get('X-PREVIEW');
        if ($preview === '1') {
            $this->runtimeConfiguration->setPreviewMode(true);
        }
    }
}
