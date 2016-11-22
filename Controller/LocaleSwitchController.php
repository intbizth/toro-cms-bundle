<?php

namespace Toro\Bundle\CmsBundle\Controller;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Toro\Bundle\CmsBundle\Locale\Handler\LocaleChangeHandlerInterface;

final class LocaleSwitchController
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var LocaleChangeHandlerInterface
     */
    private $localeChangeHandler;

    /**
     * @param EngineInterface $templatingEngine
     * @param LocaleContextInterface $localeContext
     * @param LocaleProviderInterface $localeProvider
     * @param LocaleChangeHandlerInterface $localeChangeHandler
     */
    public function __construct(
        EngineInterface $templatingEngine,
        LocaleContextInterface $localeContext,
        LocaleProviderInterface $localeProvider,
        LocaleChangeHandlerInterface $localeChangeHandler
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->localeContext = $localeContext;
        $this->localeProvider = $localeProvider;
        $this->localeChangeHandler = $localeChangeHandler;
    }

    /**
     * @return Response
     */
    public function renderAction(Request $request)
    {
        return $this->templatingEngine->renderResponse($request->get('template'), [
            'active' => $this->localeContext->getLocaleCode(),
            'locales' => $this->localeProvider->getAvailableLocalesCodes(),
        ]);
    }

    /**
     * @param Request $request
     * @param string $code
     *
     * @return Response
     */
    public function switchAction(Request $request, $code)
    {
        if (!in_array($code, $this->localeProvider->getAvailableLocalesCodes())) {
            throw new HttpException(
                Response::HTTP_NOT_ACCEPTABLE,
                sprintf('The locale code "%s" is invalid.', $code)
            );
        }

        $this->localeChangeHandler->handle($code);

        return new RedirectResponse($request->headers->get('referer', $request->getBaseUrl()));
    }
}
