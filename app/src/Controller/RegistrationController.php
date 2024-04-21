<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[OA\Post(
        path: '/api/register',
        summary: 'Register new user',
        requestBody: new OA\RequestBody(
            request: 'application/json',
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['email', 'password'],
                    properties: [
                        new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                        new OA\Property(property: 'password', type: 'string', example: 'strongpassword'),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['User Registration'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User registered successfully',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid data',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'error', type: 'string'),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email    = $data['email']    ?? null;
        $password = $data['password'] ?? null;

        if (! $email || ! $password) {
            return new JsonResponse(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($passwordHasher->hashPassword($user, $password));


        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['message' => 'Validation failed', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Email already used.'], Response::HTTP_CONFLICT);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }
}
