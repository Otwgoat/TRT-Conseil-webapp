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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    #[Route('api/inscription', name: 'registerUser', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $passHasher, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $role = $data['role'];
        if ($role === 'recruiter') {
            $user = $serializer->deserialize($request->getContent(), Recruiter::class, 'json');
            $user->setRoles(['ROLE_RECRUITER']);
        } else if ($role === 'candidate') {
            $user = $serializer->deserialize($request->getContent(), Candidate::class, 'json');
            $user->setRoles(['ROLE_CANDIDATE']);
        } else {
            return new JsonResponse('Veuillez renseigner un rôle valide', Response::HTTP_BAD_REQUEST, [], true);
        }
        $hashedPassword = $passHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }
        $em->persist($user);
        $approvalRequest = new ApprovalRequest();
        $approvalRequest->setUserId($user);
        $approvalRequest->setApproved(false);
        $em->persist($approvalRequest);
        $em->flush();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

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
    public function updateUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, User $currentUser): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $role = $data['role'];
        if ($role === 'recruiter') {
            $updatedUser = $serializer->deserialize($request->getContent(), Recruiter::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        } else if ($role === 'candidate') {
            $updatedUser = $serializer->deserialize($request->getContent(), Candidate::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        } else if ($role === 'consultant') {
            $updatedUser = $serializer->deserialize($request->getContent(), Consultant::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        } else {
            return new JsonResponse('Veuillez renseigner un rôle valide', Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($updatedUser);
        $em->flush();

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
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function createConsultant(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $passHasher, UrlGeneratorInterface $urlGenerator): JsonResponse
    {

        $user = $serializer->deserialize($request->getContent(), Consultant::class, 'json');
        $user->setRoles(['ROLE_CONSULTANT']);
        $hashedPassword = $passHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }
        $em->persist($user);
        $em->flush();
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route("api/telechargement-cv", name: "downloadCv", methods: ["PUT"])]
    #[IsGranted('ROLE_CANDIDATE', message: 'Vous n\'avez pas les droits pour accéder à cette ressource')]
    public function downloadCv(EntityManagerInterface $em, Security $security, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cvPath = $data['cvPath'];
        $currentUser = $security->getUser();
        if ($currentUser instanceof Candidate) {
            $currentUser->setCvPath($cvPath);
        } else {
            return new JsonResponse('Vous n\'avez pas les droits pour accéder à cette ressource', Response::HTTP_FORBIDDEN, [], true);
        }

        $em->persist($currentUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
