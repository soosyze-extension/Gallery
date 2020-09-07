<?php

namespace SoosyzeExtension\Gallery\Service;

class HookNode
{
    public function hookNodeEntityPictureShow(&$entity)
    {
        $entity->addPathOverride(dirname(__DIR__) . '/Views/');
    }
}
