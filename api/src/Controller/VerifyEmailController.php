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

    public function __invoke(Request $request) {
        $id = $request->get('id');
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        try {
            $this->userEmailService->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e) {
            return new JsonResponse(['message' => $e->getReason()], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['message' => 'Email successfully verified'], Response::HTTP_OK);
    }
}
