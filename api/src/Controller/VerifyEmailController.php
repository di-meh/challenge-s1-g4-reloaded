<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[AsController]
class VerifyEmailController extends AbstractController {

    public function __construct(
        private VerifyEmailHelperInterface $helper,
        private MailerInterface $mailer,
        private UserRepository $userRepository,
        private UserEmailService $userEmailService)
    {
    }
    #[Route('/api/verify_email', name: 'api_verify_email', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        // TODO soit rediriger vers le front, soit faire en sorte que l'url générée soit la bonne
        $id = $request->get('id');
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        try {
            $this->userEmailService->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e) {
            return new JsonResponse(['message' => $e->getReason(), "uri" => $request->getUri()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(['message' => 'Email successfully verified'], Response::HTTP_OK);
    }
}
