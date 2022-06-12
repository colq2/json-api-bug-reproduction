<?php

namespace App\JsonApi\Comments;

use CloudCreativity\LaravelJsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected string $resourceType = 'comments';

    /**
     * @param \App\Models\Comment $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId(object $resource): string
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Comment $resource
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