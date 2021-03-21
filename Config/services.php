<?php

return [
    'gallery.extend'    => [
        'class' => 'SoosyzeExtension\Gallery\Extend',
        'hooks' => [
            'install.user' => 'hookInstallUser'
        ]
    ],
    'gallery.hook.app'  => [
        'class'     => 'SoosyzeExtension\Gallery\Hook\App',
        'arguments' => [ '@core' ],
        'hooks'     => [
            'app.response.after' => 'hookResponseAfter'
        ]
    ],
    'gallery.hook.node' => [
        'class' => 'SoosyzeExtension\Gallery\Hook\Node',
        'hooks' => [
            'node.entity.picture_gallery.show' => 'hookNodeEntityPictureShow'
        ]
    ]
];
