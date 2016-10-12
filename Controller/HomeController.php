<?php

namespace Toro\Bundle\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $channel = $this->get('sylius.context.channel')->getChannel();
        $homePage = $this->get('toro.repository.page')->findOneBy([
            'channel' => $channel,
            'published' => true,
            'slug' => '/',
        ]);

        if ($homePage) {
            return $this->forward('toro.controller.page:viewAction', ['entity' => $homePage]);
        }

        return $this->render('ToroCmsBundle::index.html.twig');
    }
}
