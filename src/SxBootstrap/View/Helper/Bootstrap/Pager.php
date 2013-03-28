<?php

namespace SxBootstrap\View\Helper\Bootstrap;

use SxBootstrap\Html\HtmlElement;
use Zend\Paginator\Paginator;

/**
 * The ViewHelper that creates a pager.
 */
class Pager extends AbstractElementHelper
{

    /**
     * @var \Zend\Paginator\Paginator
     */
    protected $paginator;

    /**
     * @var bool
     */
    protected $align = false;

    /**
     * @var string
     */
    protected $prevLabel = '&larr; Previous';

    /**
     * @var string
     */
    protected $nextLabel = 'Next &rarr;';

    /**
     * @var array
     */
    protected $routeParams = array();

    /**
     * @var string|null
     */
    protected $route = null;

    /**
     * @param \Zend\Paginator\Paginator $paginator
     *
     * @return Pagination
     */
    public function __invoke(Paginator $paginator)
    {
        $pagerElement = new HtmlElement('ul');

        $pagerElement->addClass('pager');
        $this->setPaginator($paginator);
        $this->setElement($pagerElement);

        return clone $this;
    }

    /**
     * Return the HTML string of this HTML element
     *
     * @return string
     */
    public function render()
    {
        $urlHelper      = $this->getView()->plugin('url');
        $paginationData = $this->paginator->getPages();
        $previous       = $this->getElement()->spawnChild('li');
        $next           = $this->getElement()->spawnChild('li');
        $previousAnchor = $previous->spawnChild('a')->setContent($this->getPrevLabel());
        $nextAnchor     = $next->spawnChild('a')->setContent($this->getNextLabel());

        if (empty($paginationData->previous)) {
            $previous->addClass('disabled');
        } else {
            $previousAnchor->addAttribute('href', $urlHelper(
                $this->getRoute(),
                array_merge(
                    array('page' => $paginationData->previous),
                    $this->getRouteParams()
                )
            ));
        }

        if (empty($paginationData->next)) {
            $next->addClass('disabled');
        } else {
            $nextAnchor->addAttribute('href', $urlHelper(
                $this->getRoute(),
                array_merge(
                    array('page' => $paginationData->next),
                    $this->getRouteParams()
                )
            ));
        }

        if ($this->align) {
            $previous->addClass('previous');
            $next->addClass('next');
        }

        return $this->getElement()->render();
    }

    /**
     * @param $route
     *
     * @return Pager
     */
    public function setRoute($route)
    {
        $this->route = (string)$route;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param array $routeParams
     *
     * @return Pager
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @param $nextLabel
     *
     * @return Pager
     */
    public function setNextLabel($nextLabel)
    {
        $this->nextLabel = $nextLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getNextLabel()
    {
        return $this->translateLabel($this->nextLabel);
    }

    /**
     * @param $prevLabel
     *
     * @return Pager
     */
    public function setPrevLabel($prevLabel)
    {
        $this->prevLabel = $prevLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrevLabel()
    {
        return $this->translateLabel($this->prevLabel);
    }

    /**
     * @param $label
     *
     * @return string
     */
    protected function translateLabel($label)
    {
        if (null !== ($translator = $this->getTranslator())) {
            $label = $translator->translate(
                $label, $this->getTranslatorTextDomain()
            );
        }

        return $label;
    }

    /**
     * @param \Zend\Paginator\Paginator $paginator
     *
     * @return Pagination
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * align each link to the sides
     *
     * @param bool $align
     *
     * @return bool
     */
    public function align($align = true)
    {
        $this->align = (bool)$align;

        return $this;
    }

}
