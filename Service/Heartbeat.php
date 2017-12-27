<?php

namespace mztx\ShinagePlayerBundle\Service;

use GuzzleHttp\Exception\ClientException;
use mztx\ShinagePlayerBundle\Entity\HeartbeatAnswer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Heartbeat
{
    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var RuntimeConfiguration */
    protected $runtimeConfiguration;

    /**
     * Heartbeat constructor.
     *
     * @param UrlBuilder $urlBuilder
     * @param RuntimeConfiguration $runtimeConfiguration
     */
    public function __construct(UrlBuilder $urlBuilder, RuntimeConfiguration $runtimeConfiguration)
    {
        $this->urlBuilder = $urlBuilder;
        $this->runtimeConfiguration = $runtimeConfiguration;
    }


    public function beat()
    {
        $url = $this->urlBuilder->getControllerUrl('heartbeat', $this->runtimeConfiguration->getScreenGuid());

        try {
            $client = new \GuzzleHttp\Client(['connect_timeout' => 5]);
            $headers = [];
            if ($this->runtimeConfiguration->isPreviewMode()) {
                $headers['X-PREVIEW'] = '1';
            }
            $res = $client->request('GET', $url, ['headers' => $headers]);

            $answerJson = $res->getBody()->getContents();

            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);
            $answerObject = $serializer->deserialize($answerJson, HeartbeatAnswer::class, 'json');

            return $answerObject;
        } catch (ClientException $ex) {
            return '{"status": "error", "code": "'.$ex->getResponse()->getStatusCode().
                '", "message": "'.$ex->getMessage().'"}';
        } catch (\Exception $ex) {
            return '{"status": "error", "type": "'.get_class($ex).'","message": "'.$ex->getMessage().'"}';
        }
    }
}
