<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\Channel as BaseChannel;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface as BaseTaxonInterface;

class Channel extends BaseChannel implements ChannelInterface
{
    /**
     * @var LocaleInterface
     */
    protected $defaultLocale;

    /**
     * @var LocaleInterface[]|Collection
     */
    protected $locales;

    /**
     * @var BaseTaxonInterface[]|Collection
     */
    protected $taxons;

    /**
     * @var string
     */
    protected $themeName;

    public function __construct()
    {
        parent::__construct();

        $this->locales = new ArrayCollection();
        $this->taxons = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function setThemeName($themeName)
    {
        $this->themeName = $themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultLocale(LocaleInterface $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocales(Collection $locales)
    {
        $this->locales = $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function addLocale(LocaleInterface $locale)
    {
        if (!$this->hasLocale($locale)) {
            $this->locales->add($locale);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeLocale(LocaleInterface $locale)
    {
        if ($this->hasLocale($locale)) {
            $this->locales->removeElement($locale);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasLocale(LocaleInterface $locale)
    {
        return $this->locales->contains($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxons()
    {
        return $this->taxons;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxons(Collection $taxons)
    {
        $this->taxons = $taxons;
    }

    /**
     * {@inheritdoc}
     */
    public function addTaxon(BaseTaxonInterface $taxon)
    {
        if (!$this->hasTaxon($taxon)) {
            $this->taxons->add($taxon);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeTaxon(BaseTaxonInterface $taxon)
    {
        if ($this->hasTaxon($taxon)) {
            $this->taxons->removeElement($taxon);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasTaxon(BaseTaxonInterface $taxon)
    {
        return $this->taxons->contains($taxon);
    }
}
