<?php
/**
 * Created by PhpStorm.
 * User: michi
 * Date: 01.11.17
 * Time: 11:34
 */

namespace mztx\ShinagePlayerBundle\Command;

use GuzzleHttp\Client;
use mztx\ShinagePlayerBundle\Service\RuntimeConfiguration;
use mztx\ShinagePlayerBundle\Service\UrlBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UploadScreenshotCommand extends Command
{
    /** @var UrlBuilder */
    private $urlBuilder;

    /** @var RuntimeConfiguration */
    private $runtimeConfiguration;

    /**
     * UploadScreenshopCommand constructor.
     * @param UrlBuilder           $urlBuilder
     * @param RuntimeConfiguration $runtimeConfiguration
     */
    public function __construct(UrlBuilder $urlBuilder, RuntimeConfiguration $runtimeConfiguration)
    {
        $this->urlBuilder = $urlBuilder;
        $this->runtimeConfiguration = $runtimeConfiguration;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('shinage:player:screenshot:upload')
            ->addArgument('file', InputArgument::REQUIRED, 'Image file to upload.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        $client = new Client();
        $client->post($this->urlBuilder->getControllerUrl('screenshot'),
            [
                'multipart' => [
                    [
                        'name'     => 'FileContents',
                        'contents' => file_get_contents($file),
                        'filename' => 'screenshot.png'
                    ],
                    [
                        'name'     => 'screen',
                        'contents' => $this->runtimeConfiguration->getScreenGuid()
                    ]
                ],
            ]);
        return 0;
    }
}
