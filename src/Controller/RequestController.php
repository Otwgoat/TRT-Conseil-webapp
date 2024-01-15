<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Recruiter;
use App\Entity\ApprovalRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApprovalRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestController extends AbstractController
{
    #[Route('api/requetes', name: 'getRequests', methods: ['GET'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getRequests(SerializerInterface $si, ApprovalRequestRepository $arr): JsonResponse
    {
        $requests = $arr->findAll();
        $jsonRequests = $si->serialize($requests, 'json', ['groups' => 'getRequests', 'getUsers']);
        return new JsonResponse($jsonRequests, Response::HTTP_OK, [], true);
    }

    #[Route('api/requete/{id}', name: 'getRequest', methods: ['GET'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getRequest(SerializerInterface $si, ApprovalRequestRepository $arr, int $id): JsonResponse
    {
        $request = $arr->find($id);
        $jsonRequest = $si->serialize($request, 'json', ['groups' => 'getRequests', 'getUsers']);
        return new JsonResponse($jsonRequest, Response::HTTP_OK, [], true);
    }

    #[Route('api/requete/{id}', name: 'updateRequest', methods: ['PUT'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function updateRequest(Request $request,  EntityManagerInterface $em, ApprovalRequest $currentAr, ApprovalRequestRepository $arr): JsonResponse
    {
        $approveRequest = $arr->find($request->get('id'));
        $approveRequest->setApproved(true);
        $user = $approveRequest->getUserId();
        if ($user instanceof Candidate) {
            $user->setApproved(true);
        } elseif ($user instanceof Recruiter) {
            $user->setApproved(true);
        }
        $em->persist($approveRequest);
        $em->persist($user);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('api/requete/{id}', name: 'deleteRequest', methods: ['DELETE'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function deleteRequest(EntityManagerInterface $em, ApprovalRequest $currentAr): JsonResponse
    {
        $em->remove($currentAr);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
