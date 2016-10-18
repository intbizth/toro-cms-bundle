<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

abstract class Option implements OptionInterface
{
    use TimestampableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var string
     */
    protected $templating;

    /**
     * @var string
     */
    protected $style;

    /**
     * @var string
     */
    protected $script;

    /**
     * @var array
     */
    protected $translation = [];

    /**
     * @var string
     */
    protected $compiled;

    /**
     * @var \DateTime
     */
    protected $compiledAt;

    /**
     * @var string
     */
    protected $template;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompiled()
    {
        return $this->compiled;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompiled($compiled)
    {
        $this->compiled = $compiled;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompiledAt()
    {
        return $this->compiledAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompiledAt(\DateTime $compiledAt)
    {
        $this->compiledAt = $compiledAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function isTranslatable() : bool
    {
        return !empty($this->translation);
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateStrategy()
    {
        if (array_key_exists('template_strategy', $this->data)) {
            return $this->data['template_strategy'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateVar($default = 'page')
    {
        if (array_key_exists('template_var', $this->data)) {
            return $this->data['template_var'] ?: $default;
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function isNeedToCompile(): bool
    {
        if (!$this->compiledAt || !$this->compiled) {
            return true;
        }

        // cache gone
        if ($this->compiled && !file_exists($this->compiled)) {
            return true;
        }

        // outdated
        return $this->updatedAt->getTimestamp() > $this->compiledAt->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * {@inheritdoc}
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * {@inheritdoc}
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * {@inheritdoc}
     */
    public function setScript($script)
    {
        $this->script = $script;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation(): array
    {
        return $this->translation;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslation(array $translation)
    {
        $this->translation = $translation;
    }
}
