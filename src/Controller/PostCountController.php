<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;

class PostCountController
{
    public function __construct(private PostRepository $postRepository)
    {
    }

    public function __invoke(Request $request): int
    {
        $onlineQuery = $request->get('online');
        $conditions = [];
        if (null !== $onlineQuery) {
            $conditions = ['online' => '1' === $onlineQuery];
        }

        return $this->postRepository->count($conditions);
    }
}
