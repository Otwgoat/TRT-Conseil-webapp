<?php

namespace App\Controller;

use App\Entity\User;

use App\Entity\Candidate;

use App\Entity\Recruiter;
use App\Entity\Consultant;
use App\Entity\ApprovalRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    #[Route('api/inscription', name: 'registerUser', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $passHasher, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        // Decode the request content
        $data = json_decode($request->getContent(), true);
        $role = $data['role'];

        // Create a new user based on the role
        if ($role === 'recruiter') {
            $user = $serializer->deserialize($request->getContent(), Recruiter::class, 'json');
            $user->setRoles(['ROLE_RECRUITER']);
        } else if ($role === 'candidate') {
            $user = $serializer->deserialize($request->getContent(), Candidate::class, 'json');
            $user->setRoles(['ROLE_CANDIDATE']);
        } else {
            // Return an error response if an invalid role is provided
            return new JsonResponse('Please provide a valid role', Response::HTTP_BAD_REQUEST, [], true);
        }

        // Hash the user's password
        $hashedPassword = $passHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        // Validate the user entity
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        // Persist the user entity and create an approval request
        $em->persist($user);
        $approvalRequest = new ApprovalRequest();
        $approvalRequest->setUserId($user);
        $approvalRequest->setApproved(false);
        $em->persist($approvalRequest);
        $em->flush();

        // Serialize the user entity and generate the location URL
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Return a success response with the serialized user entity and the location URL
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('api/utilisateurs/{id}', name: 'detailUser', methods: ['GET'])]
    public function getOneUser(SerializerInterface $serializer, User $user, Security $security): JsonResponse
    {
        $currentUser = $security->getUser();
        if ($currentUser !== $user) {
            return new JsonResponse('Seules vos données personelles sont accessibles.', Response::HTTP_FORBIDDEN, [], true);
        }
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers', 'getRequests']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('api/utilisateurs', name: 'listUsers', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function getAllUsers(SerializerInterface $serializer, UserRepository $ur): JsonResponse
    {
        $users = $ur->findAll();
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'getUsers']);
        return new JsonResponse($jsonUsers, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('api/utilisateurs/{id}', name: 'updateUser', methods: ['PUT'])]
    public function updateUser(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, User $currentUser): JsonResponse
    {
        // Decode the request content
        $data = json_decode($request->getContent(), true);

        // Get the role from the request data
        $role = $data['role'];

        // Deserialize the request content based on the role and populate the current user object
        if ($role === 'recruiter') {
            $updatedUser = $serializer->deserialize($request->getContent(), Recruiter::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        } else if ($role === 'candidate') {
            $updatedUser = $serializer->deserialize($request->getContent(), Candidate::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        } else if ($role === 'consultant') {
            $updatedUser = $serializer->deserialize($request->getContent(), Consultant::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        } else {
            // Return an error response if an invalid role is provided
            return new JsonResponse('Please provide a valid role', Response::HTTP_BAD_REQUEST, [], true);
        }

        // Validate the updated user entity
        $errors = $validator->validate($updatedUser);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        // Persist the updated user entity
        $em->persist($updatedUser);
        $em->flush();

        // Return a success response
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('api/utilisateurs/{id}', name: 'deleteUser', methods: ['DELETE'])]
    #[IsGranted('ROLER_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route("api/utilisateur-connecte", name: "getConnectedUser", methods: ["GET"])]
    public function getConnectedUser(SerializerInterface $serializer, Security $security): JsonResponse
    {
        $user = $security->getUser();
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route("api/creation-consultant", name: "createConsultant", methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN', message: 'You do not have the rights to access this resource')]
    public function createConsultant(MailerInterface $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $passHasher, UrlGeneratorInterface $urlGenerator): JsonResponse
    {

        // Deserialize the request content into a Consultant object
        $user = $serializer->deserialize($request->getContent(), Consultant::class, 'json');
        $user->setRoles(['ROLE_CONSULTANT']);

        // Generate a random password for the user
        $randomPassword = bin2hex(random_bytes(10));
        $hashedPassword = $passHasher->hashPassword($user, $randomPassword);
        $user->setPassword($hashedPassword);

        // Get the user's email address
        $userMail = $user->getEmail();

        // Validate the user entity
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        // Send a welcome email to the user with the randomly generated password
        $email = (new Email())
            ->from('lucas.jouffroy@gmail.com')
            ->to($userMail)
            ->subject('Welcome to TRT-Conseil')
            ->text('Welcome to TRT-Conseil, your account has been successfully created. You can now log in to your personal space. Here is the password assigned to you: ' . $randomPassword . '. You will be able to change it soon, but for now, please keep it safe. Best regards, Your TRT-Conseil team.');
        $mailer->send($email);

        // Persist the user entity
        $em->persist($user);
        $em->flush();

        // Serialize the user entity and return it in the response
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);

        // Generate the URL for the user's detail page
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Return the JSON response with the user entity and the location header
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route("api/telechargement-cv", name: "downloadCv", methods: ["PUT"])]
    #[IsGranted('ROLE_CANDIDATE', message: 'You do not have the rights to access this resource')]
    public function downloadCv(EntityManagerInterface $em, Security $security, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cvPath = $data['cvPath'];
        $currentUser = $security->getUser();
        if ($currentUser instanceof Candidate) {
            $currentUser->setCvPath($cvPath);
        } else {
            return new JsonResponse("Vous n'avez pas l'autorisation d'accéder à cette fontion.", Response::HTTP_FORBIDDEN, [], true);
        }

        $em->persist($currentUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route("api/modifier-mot-de-passe", name: "updatePassword", methods: ["PUT"])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function updatePassword(Request $request, UserPasswordHasherInterface $passHasher, EntityManagerInterface $em, Security $security): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newPassword = $data['newPassword'];
        $currentUser = $security->getUser();
        if ($currentUser instanceof Candidate || $currentUser instanceof Recruiter || $currentUser instanceof Consultant) {
            $hashedPassword = $passHasher->hashPassword($currentUser, $newPassword);
            $currentUser->setPassword($hashedPassword);
        } else {
            return new JsonResponse('Vous n\'avez pas les droits pour accéder à cette ressource', Response::HTTP_FORBIDDEN, [], true);
        }

        $em->persist($currentUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
