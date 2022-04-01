<?php

namespace App\Controller\v1;

use App\Entity\Comment;
use App\Manager\Comment\CommentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentController extends AbstractController
{
    protected CommentManager $commentManager;

    public function __construct(
        CommentManager $commentManager
    ) {
        $this->commentManager = $commentManager;
    }

    #[Route('/comments/page/{page}', name: 'app_comment', methods: ["GET"])]
    public function pageComments(?int $page = null): JsonResponse
    {
        return $this->commentManager->getCommentsByPage($page);
    }

}
