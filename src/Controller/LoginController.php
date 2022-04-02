<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    private UserRepository $repository;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    #[Route('/login', name: 'app_login', methods: 'POST')]
    public function index(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!key_exists('username', $data) || !key_exists('password', $data)) {
            return new JsonResponse(
                ['error' => 'Favor enviar usuário e senha'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = $this->repository->findOneBy(criteria: [
            'username' => $data['username']
        ]);
        if (!$user) {
            return $this->returnError();
        }

        if (!$this->encoder->isPasswordValid($user, $data['password'])) {
            return $this->returnError();
        }

        $token = JWT::encode(['username' => $user->getUserIdentifier()], 'chave', 'HS256');

        return new JsonResponse([
            'accessToken' => $token
        ]);
    }

    private function returnError(): JsonResponse
    {
        return new JsonResponse(
            ['error' => 'Usuário ou senha inválidos'],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
