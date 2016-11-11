<?php

namespace Toro\Bundle\CmsBundle\Routing;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use Toro\Bundle\CmsBundle\Doctrine\ORM\PageFinderRepositoryInterface;
use Toro\Bundle\CmsBundle\Model\PageInterface;

class UrlMatcher extends RedirectableUrlMatcher
{
    /**
     * @var PageFinderRepositoryInterface
     */
    private $repository;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @param PageFinderRepositoryInterface $repository
     */
    public function setRepository(PageFinderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param LocaleContextInterface $localeContext
     */
    public function setLocaleContext(LocaleContextInterface $localeContext)
    {
        $this->localeContext = $localeContext;
    }

    /**
     * @param ChannelContextInterface $channelContext
     */
    public function setChannelContext(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isNotCmsRoute(Request $request)
    {
        return !in_array($request->get('_route'), ['toro_cms_page', 'toro_cms_partial_page']);
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $this->request = $request;

        /** @var PageInterface $page */
        if ($this->isNotCmsRoute($request) && $page = $this->findPage($request->getPathInfo())) {
            $ret = [
                '_controller' => 'toro.controller.page:viewAction',
                'template' => 'ToroCmsBundle::show.html.twig',
                'slug' => $page,
                '_route' => $page->isPartial() ? 'toro_cms_partial_page' : 'toro_cms_page',
            ];
        } else {
            $ret = $this->match($request->getPathInfo());
        }

        $this->request = null;

        return $ret;
    }

    private function findPage($slug)
    {
        return $this->repository->findPageForDisplay([
            'slug' => $slug,
            'locale' => $this->localeContext->getLocaleCode(),
            'channel' => $this->channelContext->getChannel(),
        ]);
    }
}
