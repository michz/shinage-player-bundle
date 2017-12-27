<?php
/**
 * @author   :  Michael Zapf <m.zapf@mtx.de>
 * @date     :  25.06.17
 * @time     :  09:38
 */

namespace mztx\ShinagePlayerBundle\Service;

use GuzzleHttp\Exception\ClientException;

class Remote
{
    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var RuntimeConfiguration */
    protected $runtimeConfiguration;

    /**
     * Remote constructor.
     *
     * @param UrlBuilder           $urlBuilder
     * @param RuntimeConfiguration $runtimeConfiguration
     */
    public function __construct(UrlBuilder $urlBuilder, RuntimeConfiguration $runtimeConfiguration)
    {
        $this->urlBuilder = $urlBuilder;
        $this->runtimeConfiguration = $runtimeConfiguration;
    }

    public function getPresentation($id)
    {
        $url = $this->urlBuilder->getControllerUrl('presentation', $id);
        if (isset($_GET['callback'])) {
            $url .= '?callback='.$_GET['callback'];
        }

        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url);

            $answerJson = $res->getBody()->getContents();
            return $answerJson;
        } catch (ClientException $ex) {
            return '{"status": "error", "code": "'.$ex->getResponse()->getStatusCode().
                '", "message": "'.$ex->getMessage().'"}';
        } catch (\Exception $ex) {
            return '{"status": "error", "type": "'.get_class($ex).'","message": "'.$ex->getMessage().'"}';
        }
    }
}
