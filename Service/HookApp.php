<?php

namespace SoosyzeExtension\Gallery\Service;

class HookApp
{
    /**
     * @var \Soosyze\App
     */
    protected $core;

    public function __construct($core)
    {
        $this->core = $core;
    }

    public function hookResponseAfter($request, &$response)
    {
        if (!($response instanceof \SoosyzeCore\Template\Services\Templating)) {
            return;
        }
        $vendor = $this->core->getPath('modules_contributed', 'app/modules', false) . '/Gallery/vendor/simplelightbox/';
        $assets = $this->core->getPath('modules_contributed', 'app/modules', false) . '/Gallery/Assets/';
        $script = $response->getBlock('this')->getVar('scripts');
        $script .= '<script src="' . $vendor . 'dist/simple-lightbox.min.js"></script>';
        $script .= '<script src="' . $assets . 'script.js"></script>';

        $styles = $response->getBlock('this')->getVar('styles');
        $styles .= '<link rel="stylesheet" href="' . $vendor . 'dist/simple-lightbox.min.css">' . PHP_EOL;
        $styles .= '<link rel="stylesheet" href="' . $assets . 'style.css">' . PHP_EOL;

        $response->view('this', [ 'scripts' => $script, 'styles' => $styles ]);
    }
}
