<?php

namespace SoosyzeExtension\Gallery;

use Psr\Container\ContainerInterface;
use Queryflatfile\TableBuilder;

class Installer extends \SoosyzeCore\System\Migration
{
    protected $pathContent;

    public function __construct()
    {
        $this->pathContent = __DIR__ . '/Views/Content/';
    }

    public function getDir()
    {
        return __DIR__ . '/composer.json';
    }

    public function boot()
    {
        $this->loadTranslation('fr', __DIR__ . '/Lang/fr/main.json');
    }

    public function install(ContainerInterface $ci)
    {
        $ci->schema()
            ->createTableIfNotExists('entity_page_gallery', function (TableBuilder $table) {
                $table->increments('page_gallery_id')
                ->text('body');
            })
            ->createTableIfNotExists('entity_picture_gallery', function (TableBuilder $table) {
                $table->increments('picture_gallery_id')
                ->integer('page_gallery_id')
                ->string('title')
                ->string('image', 512)->valueDefault('')
                ->integer('weight')->valueDefault(1);
            });

        $ci->query()
            ->insertInto('field', [ 'field_name', 'field_type' ])
            ->values([ 'field_name' => 'title', 'field_type' => 'text' ])
            ->values([ 'field_name' => 'picture_gallery', 'field_type' => 'one_to_many' ])
            ->execute();

        /* Champs de la node. */
        $idTitle  = $ci->query()
                ->from('field')
                ->where('field_name', 'title')
                ->fetch()[ 'field_id' ];
        $idImage  = $ci->query()
                ->from('field')
                ->where('field_name', 'image')
                ->fetch()[ 'field_id' ];
        $idWeight = $ci->query()
                ->from('field')
                ->where('field_name', 'weight')
                ->fetch()[ 'field_id' ];

        /* Champs de l'entity. */
        $idBody     = $ci->query()
                ->from('field')
                ->where('field_name', 'body')
                ->fetch()[ 'field_id' ];
        $idRelation = $ci->query()
                ->from('field')
                ->where('field_name', 'picture_gallery')
                ->fetch()[ 'field_id' ];

        $ci->query()
            ->insertInto('node_type', [
                'node_type',
                'node_type_name',
                'node_type_description',
                'node_type_icon'
            ])
            ->values([
                'node_type'             => 'page_gallery',
                'node_type_name'        => 'Gallery',
                'node_type_description' => 'Create an image gallery.',
                'node_type_icon'        => 'fa fa-images'
            ])
            ->execute();

        $ci->query()
            ->insertInto('node_type_field', [
                'node_type', 'field_id', 'field_weight', 'field_label', 'field_rules',
                'field_option', 'field_default_value', 'field_show'
            ])
            ->values([
                'picture_gallery', $idTitle, 1, 'Title', 'required|string|max:512',
                '',
                null, true
            ])
            ->values([
                'picture_gallery', $idImage, 2, 'Image', 'required_without:file-image-name|!required|image|max:800kb',
                '', null, true
            ])
            ->values([
                'picture_gallery', $idWeight, 3, 'Weight', 'required|int|min:1',
                '', '1', false
            ])
            ->values([
                'page_gallery', $idBody, 1, 'Body', '!required|string', '', null,
                true
            ])
            ->values([
                'page_gallery', $idRelation, 2, 'Pictures', 'required|array|max:128',
                json_encode([
                    'relation_table'  => 'entity_picture_gallery',
                    'local_key'       => 'page_gallery_id',
                    'foreign_key'     => 'page_gallery_id',
                    /* 'asc, desc, weight */
                    'sort'            => 'weight',
                    'order_by'        => 'weight',
                    'count'           => 128,
                    'field_show'      => 'title',
                    'field_type_show' => 'image'
                ]), null, true
            ])
            ->execute();
    }

    public function seeders(ContainerInterface $ci)
    {
    }

    public function hookInstall(ContainerInterface $ci)
    {
        $this->hookInstallUser($ci);
    }

    public function hookInstallUser(ContainerInterface $ci)
    {
        if ($ci->module()->has('User')) {
            $ci->query()
                ->insertInto('role_permission', [ 'role_id', 'permission_id' ])
                ->values([ 2, 'node.show.published.page_gallery' ])
                ->values([ 1, 'node.show.published.page_gallery' ])
                ->execute();
        }
    }

    public function uninstall(ContainerInterface $ci)
    {
        $ci->query()->from('node')
            ->delete()
            ->where('type', 'page_gallery')
            ->execute();
        $ci->query()->from('node_type_field')
            ->delete()
            ->where('node_type', 'page_gallery')
            ->orWhere('node_type', 'picture_gallery')
            ->execute();
        $ci->query()->from('node_type')
            ->delete()
            ->where('node_type', 'page_gallery')
            ->execute();
        $ci->query()->from('field')
            ->delete()
            ->where('field_name', 'picture_gallery')
            ->execute();
        $ci->schema()->dropTable('entity_page_gallery');
        $ci->schema()->dropTable('entity_picture_gallery');
    }

    public function hookUninstall(ContainerInterface $ci)
    {
        $this->hookUninstallUser($ci);
    }

    public function hookUninstallUser(ContainerInterface $ci)
    {
        if ($ci->module()->has('User')) {
            $ci->query()
                ->from('role_permission')
                ->delete()
                ->where('permission_id', 'like', '%page_gallery%')
                ->execute();
        }
    }
}
