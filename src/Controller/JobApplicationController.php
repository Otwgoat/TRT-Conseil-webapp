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
        // Get the authenticated user
        $user = $security->getUser();

        // Find approved job applications by user
        $jobApplications = $jar->findApprovedApplicationByUser(true, $user);

        // Serialize the job applications to JSON
        $jsonJobApplications = $si->serialize($jobApplications, 'json', ['groups' => 'getJobApplications']);

        // Return the JSON response
        return new JsonResponse($jsonJobApplications, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('api/candidature-id-utilisateur/{jobID}', name: 'getJobApplicationByIdByUser', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'You do not have permission to access this resource')]
    public function getJobApplicationByIdByUser(Request $request, JobApplicationRepository $jar, SerializerInterface $si, Security $security, $jobID): JsonResponse
    {
        // Get the authenticated user
        $user = $security->getUser();

        // Find the job application by user and job ID
        $jobApplication = $jar->findApplicationsByUserAndById($jobID, $user);

        // Serialize the job application to JSON
        $jsonJobApplications = $si->serialize($jobApplication, 'json', ['groups' => 'getJobApplications']);

        // Return the JSON response
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
        // Deserialize the request content into a JobApplication object
        $jobApplication = $si->deserialize($request->getContent(), JobApplication::class, 'json');

        // Validate the JobApplication object
        $errors = $validator->validate($jobApplication);
        if (count($errors) > 0) {
            return new JsonResponse($si->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        // Get the authenticated user
        $user = $security->getUser();

        // Check if the user is a Candidate and if their account is approved
        if ($user instanceof Candidate) {
            if (!$user->isApproved()) {
                return new JsonResponse('Votre compte n\'a pas encore été approuvé par un consultant.', JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
        }

        // Set the Candidate ID and Job ID for the JobApplication
        $jobApplication->setCandidateId($user);
        $data = json_decode($request->getContent(), true);
        $jobAdvertissement = $jar->find($data['jobID']);
        $jobApplication->setJobID($jobAdvertissement);

        // Create a new JobApplyApproveRequest and set its properties
        $approveRequest = new JobApplyApproveRequest();
        $approveRequest->setJobApplication($jobApplication);
        $approveRequest->setApproved(false);

        // Persist the JobApplyApproveRequest and flush the changes to the database
        $em->persist($approveRequest);
        $em->flush();

        // Serialize the JobApplication object and generate the location URL
        $jsonJobApplication = $si->serialize($jobApplication, 'json', ['groups' => 'getJobApplications']);
        $location = $ugi->generate('getJobApplication', ['id' => $jobApplication->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Return the serialized JobApplication as a JsonResponse with the location header
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
        // Find the JobApplyApproveRequest by ID
        $approveRequest = $jobApplyApproveRequestRepository->find($request->get('id'));

        // Set the approve status to true
        $approveRequest->setApproved(true);

        // Get the associated JobApplication
        $jobApplication = $jaar->getJobApplication();

        // Set the approve status of the JobApplication to true
        $jobApplication->setApproved(true);

        // Get the candidate associated with the JobApplication
        $candidate = $candidateRepo->find($jobApplication->getCandidateId());

        // Get the path of the candidate's CV
        $cvPath = $candidate->getCvPath();

        // Get the recruiter associated with the JobApplication
        $recruiter = $jobApplication->getJobID()->getRecruiterId();

        // Get the email of the recruiter
        $recruiterMail = $recruiter->getEmail();

        // Create an email with the CV attached
        $email = (new Email())
            ->from('lucas.jouffroy@gmail.com')
            ->to($recruiterMail)
            ->subject('TRT-Conseil - Nouvelle candidature approuvée')
            ->text('Bonjour, une nouvelle candidature a été approuvée pour votre annonce. Vous pouvez consulter le CV du candidat en suivant ce lien : ' + $cvPath + '. Bien cordialement, votre équipe TRT-Conseil.');


        // Send the email
        $mailer->send($email);

        // Persist the changes to the JobApplyApproveRequest and JobApplication
        $em->persist($approveRequest);
        $em->persist($jobApplication);
        $em->flush();

        // Serialize the updated JobApplyApproveRequest
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
