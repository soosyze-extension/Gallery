<?php

namespace SoosyzeExtension\Gallery\Hook;

use SoosyzeCore\Template\Services\Templating;

class App
{
    /**
     * @var \Soosyze\App
     */
    private $core;

    public function __construct($core)
    {
        $this->core = $core;
    }

    public function hookResponseAfter($request, &$response)
    {
        if (!($response instanceof Templating)) {
            return;
        }
        $vendor = $this->core->getPath('modules_contributed', 'app/modules', false) . '/Gallery/vendor/simplelightbox';
        $assets = $this->core->getPath('modules_contributed', 'app/modules', false) . '/Gallery/Assets';

        $response->addScript('simple.lightbox', "$vendor/dist/simple-lightbox.min.js")
            ->addScript('gallery', "$assets/js/gallery.js")
            ->addStyle('simple.lightbox', "$vendor/dist/simple-lightbox.min.css")
            ->addStyle('gallery', "$assets/css/gallery.css");
    }
}
