<?php

namespace App\Controller\v1;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PageController extends AbstractController
{
    #[Route('/pages', name: 'app_page')]
    public function collection(PageRepository $pageRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($pageRepository->findAll(), "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
