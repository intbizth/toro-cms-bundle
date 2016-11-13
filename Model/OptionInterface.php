<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface OptionInterface extends ResourceInterface, TimestampableInterface
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @param array $data
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function getTemplating();

    /**
     * @param mixed $templating
     */
    public function setTemplating($templating);

    /**
     * @return string
     */
    public function getCompiled();

    /**
     * @param string $compiled
     */
    public function setCompiled($compiled);

    /**
     * @return \DateTime
     */
    public function getCompiledAt();

    /**
     * @param \DateTime $compiledAt
     */
    public function setCompiledAt(\DateTime $compiledAt);

    /**
     * @return string
     */
    public function getTemplateStrategy();

    /**
     * @param string $default
     *
     * @return string
     */
    public function getTemplateVar($default = 'page');

    /**
     * @return string|null
     */
    public function getTemplate();

    /**
     * @param string $template
     */
    public function setTemplate($template);

    /**
     * @return OptionableInterface
     */
    public function getOptionable();

    /**
     * @param OptionableInterface|null $optionable
     */
    public function setOptionable(OptionableInterface $optionable = null);

    /**
     * @return boolean
     */
    public function isNeedToCompile(): bool;

    /**
     * @return boolean
     */
    public function isTranslatable(): bool;

    /**
     * @return string
     */
    public function getStyle();

    /**
     * @param string $style
     */
    public function setStyle($style);

    /**
     * @return string
     */
    public function getScript();

    /**
     * @param string $script
     */
    public function setScript($script);

    /**
     * @return array
     */
    public function getTranslation(): array;

    /**
     * @param array $translation
     */
    public function setTranslation(array $translation);
}
