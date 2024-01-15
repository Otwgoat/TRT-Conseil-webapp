<?php

namespace App\Controller;

use App\Entity\JobAdvertissement;
use App\Entity\JobApproveRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\JobAdvertissementRepository;
use App\Repository\JobApproveRequestRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\SecurityBundle\Security;

class JobAdvertissementController extends AbstractController
{
    #[Route('api/annonces', name: 'getJobAdvertissements', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobAdvertissements(JobAdvertissementRepository $jar, SerializerInterface $si): JsonResponse
    {
        $jobAdvertissements = $jar->findByApprovedAdvertissement(true);
        $jsonJobAdvertissements = $si->serialize($jobAdvertissements, 'json', ['groups' => 'getJobAdvertissements']);
        return new JsonResponse($jsonJobAdvertissements, Response::HTTP_OK, [], true);
    }

    #[Route('api/annonce/{id}', name: 'getJobAdvertissement', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobAdvertissement(JobAdvertissementRepository $jar, SerializerInterface $si, int $id): JsonResponse
    {
        $jobAdvertissement = $jar->find($id);
        $jsonJobAdvertissement = $si->serialize($jobAdvertissement, 'json', ['groups' => 'getJobAdvertissements']);
        return new JsonResponse($jsonJobAdvertissement, Response::HTTP_OK, [], true);
    }

    #[Route('api/annonces', name: 'createJobAdvertissement', methods: ['POST'])]
    #[IsGranted('ROLE_RECRUITER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function createJobAdvertissement(Request $request, SerializerInterface $si, EntityManagerInterface $em, ValidatorInterface $validator, UrlGeneratorInterface $urlGenerator, Security $security): JsonResponse
    {
        $advertissement = $si->deserialize($request->getContent(), JobAdvertissement::class, 'json');
        $errors = $validator->validate($advertissement);
        if (count($errors) > 0) {
            return new JsonResponse($si->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }
        $user = $security->getUser();
        $advertissement->setRecruiterId($user);
        $em->persist($advertissement);
        $approveRequest = new JobApproveRequest();
        $approveRequest->setJobID($advertissement);
        $approveRequest->setApproved(false);
        $em->persist($approveRequest);
        $em->flush();
        $jsonAdvertissement = $si->serialize($advertissement, 'json', ['groups' => 'getJobAdvertissements']);
        $location = $urlGenerator->generate('getJobAdvertissement', ['id' => $advertissement->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonAdvertissement, Response::HTTP_OK, ['Location' => $location], true);
    }

    #[Route('api/annonce/{id}', name: 'updateJobAdvertissement', methods: ['PUT'])]
    #[IsGranted('ROLE_RECRUITER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function updateJobAdvertissement(Request $request, SerializerInterface $si, EntityManagerInterface $em, JobAdvertissement $currentJobAdvertissement): JsonResponse
    {
        $updatedJobAdvertissement = $si->deserialize($request->getContent(), JobAdvertissement::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentJobAdvertissement]);
        $em->persist($updatedJobAdvertissement);
        $em->flush();
        $jsonAdvertissement = $si->serialize($currentJobAdvertissement, 'json', ['groups' => 'getJobAdvertissements']);
        return new JsonResponse($jsonAdvertissement, Response::HTTP_OK, [], true);
    }

    /********************************************************************************** */
    /********   Approve request routes *************************************************/

    #[Route('api/requete-annonce/{id}', name: 'getJobApproveRequest', methods: ['GET'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobApproveRequest(JobApproveRequest $jar, SerializerInterface $si): JsonResponse
    {
        $jsonJobAdvertissement = $si->serialize($jar, 'json', ['groups' => 'getJobApproveRequest']);
        return new JsonResponse($jsonJobAdvertissement, Response::HTTP_OK, [], true);
    }
    /****** Get all jobApproveRequest , even the approved ones */
    /******************************************************** */
    #[Route('api/requete-annonces-complet', name: 'getJobApproveRequests', methods: ['GET'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobApproveRequests(JobApproveRequestRepository $jarr, SerializerInterface $si): JsonResponse
    {
        $jobApproveRequests = $jarr->findAll();
        $jsonJobApproveRequests = $si->serialize($jobApproveRequests, 'json', ['groups' => 'getJobApproveRequests']);
        return new JsonResponse($jsonJobApproveRequests, Response::HTTP_OK, [], true);
    }
    /***** Approve a jobApproveRequest */
    /******************************** */
    #[Route('api/requete-annonce/{id}', name: 'updateJobApproveRequest', methods: ['PUT'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function updateJobApproveRequest(Request $request, SerializerInterface $si, EntityManagerInterface $em, JobApproveRequest $currentJar, JobApproveRequestRepository $jarr): JsonResponse
    {

        $jobApproveRequest = $jarr->find($request->get('id'));
        $jobApproveRequest->setApproved(true);
        $jobAdvertissement = $jobApproveRequest->getJobID();
        $jobAdvertissement->setApproved(true);
        $em->persist($jobApproveRequest);
        $em->persist($jobAdvertissement);
        $em->flush();

        $jsonJobApproveRequest = $si->serialize($jobApproveRequest, 'json', ['groups' => 'getJobApproveRequests']);
        return new JsonResponse($jsonJobApproveRequest, Response::HTTP_OK, [], true);
    }

    #[Route('api/requete-annonce/{id}', name: 'deleteJobApproveRequest', methods: ['DELETE'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function deleteJobApproveRequest(EntityManagerInterface $em, JobApproveRequest $currentJar): JsonResponse
    {
        $em->remove($currentJar);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /******************  Get all request that are pending to approve by a consultant */
    /****************************************************************************** */
    #[Route('api/requete-annonces', name: 'getUnapprovedRequest', methods: ['GET'])]
    public function getUnapprovedRequest(JobApproveRequestRepository $jarr, SerializerInterface $si): JsonResponse
    {
        $jobApproveRequests = $jarr->findUnapprovedRequest(false);
        $jsonJobApproveRequests = $si->serialize($jobApproveRequests, 'json', ['groups' => 'getJobApproveRequest']);
        return new JsonResponse($jsonJobApproveRequests, Response::HTTP_OK, [], true);
    }
}
