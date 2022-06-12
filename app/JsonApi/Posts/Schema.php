<?php

namespace App\JsonApi\Posts;

use CloudCreativity\LaravelJsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected string $resourceType = 'posts';

    /**
     * @param \App\Models\Post $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId(object $resource): string
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Post $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes(object $resource): array
    {
        return [
            'title' => $resource->title,
        ];
    }

    public function getRelationships(object $resource, bool $isPrimary, array $includedRelationships): array
    {
        return [
            'comments' => [
                self::SHOW_SELF => false,
                self::SHOW_RELATED => false,
                self::SHOW_DATA => isset($includedRelationships['comments']),
                self::DATA => function () use ($resource) {
                    return $resource->comments;
                },
            ],
        ];
    }
}