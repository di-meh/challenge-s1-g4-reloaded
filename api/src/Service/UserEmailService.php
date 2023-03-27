<?php

namespace App\Service;

use App\Entity\TokenResetPassword;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserEmailService
{
    public function __construct(private VerifyEmailHelperInterface $helper, private MailerInterface $mailer, private UserRepository $repository){}


    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailVerification(User $user): void
    {
        $signatureComponents = $this->helper->generateSignature(
            'api_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );
        $link = $signatureComponents->getSignedUrl();

        $mail = (new TemplatedEmail())
            ->subject('Veuillez confirmer votre adresse email')
            ->to(new Address($user->getEmail()))
            ->htmlTemplate('emails/verifyEmail.html.twig')
            ->context([
                'link' => $link,
            ]);

        $this->mailer->send($mail);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        $uri = $request->getUri();
//        $uri = str_replace("https", "http", $uri);
        $this->helper->validateEmailConfirmation($uri, $user->getId(), $user->getEmail());
        $user->setVerified(true);
        $this->repository->save($user, true);
    }

}
