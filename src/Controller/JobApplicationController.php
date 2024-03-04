<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\JobApplication;
use App\Entity\JobApplyApproveRequest;
use App\Repository\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\JobApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\JobAdvertissementRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\JobApplyApproveRequestRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Serializer;

class JobApplicationController extends AbstractController
{
    #[Route('api/candidatures', name: 'getJobApplications', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobApplications(JobApplicationRepository $jar, SerializerInterface $si): JsonResponse
    {
        $jobApplications = $jar->findApprovedApplication(true);
        $jsonJobApplications = $si->serialize($jobApplications, 'json', ['groups' => 'getJobApplications']);
        return new JsonResponse($jsonJobApplications, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/nombre-candidature/{jobID}', name: 'getJobApplicationsCount', methods: ['GET'])]
    public function getJobApplicationsCount(JobApplicationRepository $jar, SerializerInterface $si,  int $jobID): JsonResponse
    {
        $jobApplications = $jar->findApprovedApplicationByAd(true, $jobID);
        $jsonJobApplications = $si->serialize($jobApplications, 'json', ['groups' => 'getJobApplications']);
        return new JsonResponse($jsonJobApplications, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidatures/utilisateur', name: 'getJobApplicationsByUser', methods: ['GET'])]
    public function getJobApplicationsByUser(JobApplicationRepository $jar, SerializerInterface $si, Security $security): JsonResponse
    {
        $user = $security->getUser();
        $jobApplications = $jar->findApprovedApplicationByUser(true, $user);
        $jsonJobApplications = $si->serialize($jobApplications, 'json', ['groups' => 'getJobApplications']);
        return new JsonResponse($jsonJobApplications, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidature-id-utilisateur/{jobID}', name: 'getJobApplicationByIdByUser', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobApplicationByIdByUser(Request $request, JobApplicationRepository $jar, SerializerInterface $si, Security $security, $jobID): JsonResponse
    {
        $user = $security->getUser();

        $jobApplication = $jar->findApplicationsByUserAndById($jobID, $user);
        $jsonJobApplications = $si->serialize($jobApplication, 'json', ['groups' => 'getJobApplications']);
        return new JsonResponse($jsonJobApplications, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidature/{id}', name: 'getJobApplication', methods: ['GET'])]
    public function getJobApplication(JobApplicationRepository $jar, SerializerInterface $si, int $id): JsonResponse
    {
        $jobApplication = $jar->find($id);
        $jsonJobApplication = $si->serialize($jobApplication, 'json', ['groups' => 'getJobApplications']);
        return new JsonResponse($jsonJobApplication, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidatures', name: 'createJobApplication', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function createJobApplication(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $si, UrlGeneratorInterface $ugi, Security $security, JobAdvertissementRepository $jar): JsonResponse
    {
        $jobApplication = $si->deserialize($request->getContent(), JobApplication::class, 'json');
        $errors = $validator->validate($jobApplication);
        if (count($errors) > 0) {
            return new JsonResponse($si->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $user = $security->getUser();
        if ($user instanceof Candidate) {
            if (!$user->isApproved()) {
                return new JsonResponse('Votre compte n\'a pas encore été approuvé par un consultant.', JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
        }
        $jobApplication->setCandidateId($user);
        $data = json_decode($request->getContent(), true);
        $jobAdvertissement = $jar->find($data['jobID']);
        $jobApplication->setJobID($jobAdvertissement);
        $approveRequest = new JobApplyApproveRequest();
        $approveRequest->setJobApplication($jobApplication);
        $approveRequest->setApproved(false);
        $em->persist($approveRequest);
        $em->flush();
        $jsonJobApplication = $si->serialize($jobApplication, 'json', ['groups' => 'getJobApplications']);
        $location = $ugi->generate('getJobApplication', ['id' => $jobApplication->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonJobApplication, JsonResponse::HTTP_OK, ['Location' => $location], true);
    }

    #[Route('api/candidature/{id}', name: 'deleteJobApplication', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function deleteJobApplication(JobApplicationRepository $jar, EntityManagerInterface $em, int $id): JsonResponse
    {
        $jobApplication = $jar->find($id);
        $em->remove($jobApplication);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /*************************************************************************************** */
    /******  Applications approve request  **************************************************/
    /************************************************************************************* */
    #[Route('api/candidature-requetes', name: 'getJobApplyApproveRequests', methods: ['GET'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobApplyApproveRequests(SerializerInterface $si, JobApplyApproveRequestRepository $jaarr): JsonResponse
    {
        $jobApplyApproveRequests = $jaarr->findUnapprovedRequests();
        $jsonJobApplyApproveRequests = $si->serialize($jobApplyApproveRequests, 'json', ['groups' => 'getJobApplyApproveRequests']);
        return new JsonResponse($jsonJobApplyApproveRequests, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidature-requete/{id}', name: 'getJobApplyApproveRequest', methods: ['GET'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getJobApplyApproveRequest(SerializerInterface $si, JobApplicationRepository $jar, int $id): JsonResponse
    {
        $jobApplyApproveRequest = $jar->find($id);
        $jsonJobApplyApproveRequest = $si->serialize($jobApplyApproveRequest, 'json', ['groups' => 'getJobApplyApproveRequests']);
        return new JsonResponse($jsonJobApplyApproveRequest, JsonResponse::HTTP_OK, [], true);
    }


    /***********************************************************************************************/
    /***** Approve an Application request ******************************************************* */
    /******************************************************************************************* */
    #[Route('api/candidature-requete/{id}', name: 'updateJobApplyApproveRequest', methods: ['PUT'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function updateJobApplyApproveRequest(MailerInterface $mailer, CandidateRepository $candidateRepo, Request $request, SerializerInterface $si, EntityManagerInterface $em, JobApplyApproveRequestRepository $jobApplyApproveRequestRepository, JobApplyApproveRequest $jaar): JsonResponse
    {

        $approveRequest = $jobApplyApproveRequestRepository->find($request->get('id'));
        $approveRequest->setApproved(true);
        $jobApplication = $jaar->getJobApplication();
        $jobApplication->setApproved(true);
        $candidate = $candidateRepo->find($jobApplication->getCandidateId());
        $cvPath = $candidate->getCvPath();
        $recruiter = $jobApplication->getJobID()->getRecruiterId();
        $recruiterMail = $recruiter->getEmail();
        $email = (new Email())
            ->from('lucas.jouffroy@gmail.com')
            ->to($recruiterMail)
            ->subject('TRT-Conseil - Nouvelle candidature approuvée')
            ->text('Bonjour, une nouvelle candidature a été approuvée pour votre annonce. Vous pouvez consulter le CV du candidat en pièce jointe de ce mail. Bien cordialement, votre équipe TRT-Conseil.')
            ->attachFromPath($cvPath);
        $mailer->send($email);
        $em->persist($approveRequest);
        $em->persist($jobApplication);
        $em->flush();
        $jsonJobApplyApproveRequest = $si->serialize($approveRequest, 'json', ['groups' => 'getJobApplyApproveRequests']);

        return new JsonResponse($jsonJobApplyApproveRequest, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidature-requete/{id}', name: 'deleteJobApplyApproveRequest', methods: ['DELETE'])]
    #[IsGranted('ROLE_CONSULTANT', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function deleteJobApplyApproveRequest(EntityManagerInterface $em, JobApplyApproveRequest $currentJaar): JsonResponse
    {
        $em->remove($currentJaar);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
