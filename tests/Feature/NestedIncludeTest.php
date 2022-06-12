<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\CommentFactory;
use Database\Factories\PageFactory;
use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Tests\CreatesApplication;
use Tests\TestCase;

class NestedIncludeTest extends TestCase
{
    use CreatesApplication;
    use MakesJsonApiRequests;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_nested_include_paths()
    {
        /** @var User $user */
        $user = UserFactory::new()
            ->has(
                PostFactory::new()
                    ->count(1)
                    ->has(
                        CommentFactory::new()
                            ->count(1),
                        'comments'
                    ), 'posts'
            )
            ->has(
                PageFactory::new()->count(1),
                'pages'
            )->create();

        $post = $user->posts->first();
        $comment = $post->comments->first();
        $page = $user->pages->first();


        $expectedRelationships = [
            "posts" => [
                "data" => [
                    ["type" => "posts", "id" => "1"]
                ]
            ],
//            "comments" => [
//                "data" => [
//                    ["type" => "comments", "id" => "1"]
//                ]
//            ],
            "pages" => [
                "data" => [
                    ["type" => "pages", "id" => "1"]
                ]
            ]
        ];

        $expected = [
            'data' => [
                'type' => 'users',
                'id' => (string) $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'relationships' => $expectedRelationships,
                'links' => [
                    'self' => url('/api/v1/users/' . $user->id)
                ]
            ],
            'included' => [
                // post
                [
                    "type" => "posts",
                    "id" => (string) $post->id,
                    "attributes" => [
                        "title" => $post->title
                    ],
                    "relationships" => [
                        "comments" => [
                            "data" => [
                                ["type" => "comments", "id" => $comment->id]
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => url('/api/v1/posts/' . $post->id)
                    ]
                ],
                // comment
                [
                    "type" => "comments",
                    "id" => $comment->id,
                    "attributes" => [
                       "title" => $comment->title
                    ],
                    'links' => [
                        'self' => url('/api/v1/comments/' . $comment->id)
                    ]
                ],
                // page
                [
                    "type" => "pages",
                    "id" => $page->id,
                    "attributes" => [
                        "title" => $page->title
                    ],
                    'links' => [
                        'self' => url('/api/v1/pages/' . $page->id)
                    ]
                ]
            ]
        ];

        $response = $this->jsonApi()
            ->expects('users')
            ->includePaths('posts.comments', 'pages')
            ->get('/api/v1/users/' . $user->id);

        $response->assertJson($expected);
    }
}