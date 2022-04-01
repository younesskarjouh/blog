<?php

namespace App\Manager\Comment;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentManager implements CommentManagerInterface
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function fetchComment(int $commentId): ?Comment
    {
        return $this->commentRepository->find($commentId);
    }

    public function addComment(Comment $comment)
    {
        $this->commentRepository->add($comment, true);
    }

    public function removeComment(Comment $comment): void
    {
        $this->commentRepository->remove($comment, true);
    }

    public function getCommentsByPage(?int $page = null): JsonResponse
    {
        $comments = $this->commentRepository->getCommentsByPage($page);

        return new JsonResponse(
            $this->formatComments($comments),
            Response::HTTP_OK,
            [],
            false
        );
    }

    public function formatComments($comments): array
    {
        $formattedComments = [];
        foreach ($comments as $comment) {
            if ($comment instanceof Comment) {
                $formattedComments[] = [
                    'id' => $comment->getId(),
                    'content' => $comment->getContent(),
                    'publishedAt' => $comment->getPublishedAt()->format('d-m-Y à H:i:s'),
                    'replies' => $this->formatComments($comment->getReplies())
                ];
            }
        }

        return $formattedComments;
    }
}
