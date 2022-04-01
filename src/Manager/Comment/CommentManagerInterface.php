<?php

namespace App\Manager\Comment;

use App\Entity\Comment;

interface CommentManagerInterface
{
    public function fetchComment(int $commentId): ?Comment;

    public function getCommentsByPage(?int $page = null);

    public function addComment(Comment $comment);

    public function removeComment(Comment $comment);
}
