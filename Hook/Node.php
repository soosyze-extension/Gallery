<?php

namespace SoosyzeExtension\Gallery\Hook;

class Node
{
    public function hookNodeEntityPictureShow(&$entity)
    {
        $entity->addPathOverride(dirname(__DIR__) . '/Views/');
    }
}
