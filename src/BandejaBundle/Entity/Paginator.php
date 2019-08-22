<?php

namespace BandejaBundle\Entity;

/**
 * Paginator
 */
class Paginator
{
    /**
     * @var integer
     */
    private $max_page;

    /**
     * @var integer
     */
    private $max_register;

    /**
     * @var integer
     */
    private $byPage;

    /**
     * @var integer
     */
    private $page;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $sufix;


    /**
     * Constructor
     */
    public function __construct($max_register, $page, $byPage, $url, $sufix = null) {
        $this->byPage = $byPage;

        $this->max_register = $max_register;

        $this->page = $page;

        $this->max_page = (int) ceil($this->max_register / $this->byPage);
        $this->max_page = ! $this->max_page ? 1 : $this->max_page; // si es max_page == 0, cambia a 1

        $this->url = $url;

        $this->sufix = $sufix;
    }

    /**
     * Set max_page
     *
     * @param $max_page
     *
     * @return Paginator
     */
    public function setMaxPage($max_page) {
        $this->max_page = $max_page;

        return $this;
    }

    /**
     * Get max_page
     *
     * @return integer
     */
    public function getMaxPage() {
        return $this->max_page;
    }

    /**
     * Set url
     *
     * @param $url
     *
     * @return Paginator
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Create View
     *
     * @return string
     */
    public function createView() {
        if ($this->max_page >= 3 ) {
            if ($this->page == 1)
                $pages = range($this->page, $this->page + 2);
            else if ($this->page == $this->max_page)
                $pages = range($this->max_page - 2, $this->max_page);
            else
                $pages = range($this->page - 1, $this->page + 1);
        } else
          $pages = range(1, $this->max_page);

        $pre_class = $this->page == 1 ? ' disabled' : '';
        $post_class = $this->page == $this->max_page ? ' disabled' : '';
        $sufix = $this->sufix ? '/' . $this->sufix : '';

        $view = '<nav arial-label="Paginador"><ul class="pagination">'
              . '<li class="page-item' . $pre_class . '"><a class="page-link" href="'
              . $this->url .'/' . ($this->page - 1) . $sufix . '">Anterior</a></li>';

        foreach ($pages as $p) {
            $curr_class = $this->page == $p ? ' active' : '';

            $view .= '<li class="page-item' . $curr_class . '"><a class="page-link" href="'
                  . $this->url . '/' .$p . $sufix . '">' . $p . '</a></li>';
        }

        $view .= '<li class="page-item' . $post_class . '"><a class="page-link" href="'
              . $this->url .'/' . ($this->page + 1) . $sufix . '">Siguiente</a></li></ul></nav>';

        return $view;
    }
}
