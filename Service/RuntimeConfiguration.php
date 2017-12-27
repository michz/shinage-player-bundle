<?php
/**
 * Created by PhpStorm.
 * User: michi
 * Date: 27.12.17
 * Time: 15:50
 */

namespace mztx\ShinagePlayerBundle\Service;

class RuntimeConfiguration
{
    /** @var string */
    private $screenGuid;

    /** @var bool */
    private $previewMode = false;

    /**
     * RuntimeConfiguration constructor.
     * @param string $screenGuid
     * @param bool $previewMode
     */
    public function __construct(string $screenGuid, bool $previewMode)
    {
        $this->screenGuid = $screenGuid;
        $this->previewMode = $previewMode;
    }

    /**
     * @return string
     */
    public function getScreenGuid(): string
    {
        return $this->screenGuid;
    }

    /**
     * @param string $screenGuid
     */
    public function setScreenGuid(string $screenGuid): void
    {
        $this->screenGuid = $screenGuid;
    }

    /**
     * @param bool $previewMode
     */
    public function setPreviewMode(bool $previewMode): void
    {
        $this->previewMode = $previewMode;
    }

    /**
     * @return bool
     */
    public function isPreviewMode(): bool
    {
        return $this->previewMode;
    }
}
