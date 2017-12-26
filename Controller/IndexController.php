<?php

namespace mztx\ShinagePlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{

    public function indexAction(Request $request)
    {
        $playerHtml = __DIR__ . '/../Resources/public/player.html';
        $appContent = file_get_contents($playerHtml);
        $appContent = str_replace(
            '%%base_url%%',
            $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
            $appContent
        );
        return new Response($appContent);
    }
}
