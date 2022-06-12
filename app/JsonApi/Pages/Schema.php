<?php

namespace App\JsonApi\Pages;

use CloudCreativity\LaravelJsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected string $resourceType = 'pages';

    /**
     * @param \App\Models\Page $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId(object $resource): string
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Page $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes(object $resource): array
    {
        return [
            'title' => $resource->title
        ];
    }
}