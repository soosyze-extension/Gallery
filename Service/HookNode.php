<?php

namespace SoosyzeExtension\Gallery\Service;

class HookNode
{
    public function hookNodeEntityPictureShow(&$entity)
    {
        $entity->pathOverride(dirname(__DIR__) . '/Views/');
    }
}
