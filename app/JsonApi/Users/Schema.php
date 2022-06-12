<?php

namespace App\JsonApi\Users;

use CloudCreativity\LaravelJsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected string $resourceType = 'users';

    /**
     * @param \App\Models\User $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId(object $resource): string
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\User $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes(object $resource): array
    {
        return [
            'name' => $resource->name,
            'email' => $resource->email,
        ];
    }

    public function getRelationships(object $resource, bool $isPrimary, array $includedRelationships): array
    {
        return [
            'posts' => [
                self::SHOW_SELF => false,
                self::SHOW_RELATED => false,
                self::SHOW_DATA => isset($includedRelationships['posts']),
//                self::SHOW_DATA => true,
                self::DATA => function () use ($resource) {
                    return $resource->posts;
                },
            ],
            'pages' => [
                self::SHOW_SELF => false,
                self::SHOW_RELATED => false,
                self::SHOW_DATA => isset($includedRelationships['pages']),
//                self::SHOW_DATA => true,
                self::DATA => function () use ($resource) {
                    return $resource->pages;
                },
            ],
        ];
    }
}