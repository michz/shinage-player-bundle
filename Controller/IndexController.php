<?php

namespace mztx\ShinagePlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class IndexController extends Controller
{

    public function indexAction(Request $request)
    {
        /** @var RouterInterface $router */
        $router = $this->get('router');

        $playerHtml = __DIR__ . '/../Resources/public/player.html';
        $appContent = file_get_contents($playerHtml);
        $appContent = str_replace(
            [
                '%%base_url%%',
                '%%current_url%%'
            ],
            [
                $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
                $router->generate('shinage.player.current')
            ],
            $appContent
        );
        return new Response($appContent);
    }
}
