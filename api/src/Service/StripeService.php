<?php

namespace App\Service;

use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class StripeService {

    private StripeClient $stripeClient;

    public function __construct(
        private UserRepository $userRepository,
        private AnnoncesRepository $annoncesRepository,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer) {
        $this->stripeClient = new StripeClient($_SERVER['STRIPE_SECRET_KEY']);
    }

    public function handlePaymentSucceeded($data): void {
        $payingUser = $this->userRepository->findOneBy(['email' => $data->customer_email]);
        if ($payingUser) {
            $checkoutData = $this->stripeClient->checkout->sessions->retrieve($data->id,
                ["expand" => ["line_items"]]);
            $productId = $checkoutData->line_items->data[0]->price->product;
            $annonce = $this->annoncesRepository->findOneBy(['stripe_product_id' => $productId]);
            if ($annonce) {
                $annonce->setBuyer($payingUser);
                $payingUser->addBought($annonce);
                $this->entityManager->persist($annonce);
                $this->entityManager->persist($payingUser);
                $this->entityManager->flush();
                $payerMail = (new TemplatedEmail())
                    ->subject('Votre achat sur le site DonneArgent')
                    ->to($payingUser->getEmail())
                    ->htmlTemplate('emails/annonceBought.html.twig')
                    ->context([
                        'vendeur' => $annonce->getAnnonceOwner()->getEmail(),
                        'title' => $annonce->getTitle(),
                        'price' => $annonce->getPrice(),
                    ]);
                $vendeurMail = (new TemplatedEmail())
                    ->subject('Votre annonce a été achetée sur le site DonneArgent')
                    ->to($annonce->getAnnonceOwner()->getEmail())
                    ->htmlTemplate('emails/annonceSold.html.twig')
                    ->context([
                        'acheteur' => $payingUser->getEmail(),
                        'title' => $annonce->getTitle(),
                        'price' => $annonce->getPrice(),
                    ]);
                $this->mailer->send($payerMail);
                $this->mailer->send($vendeurMail);
            }
            else {
                throw new \Exception("Annonce not found");
            }
        }
        else {
            throw new \Exception("User not found");
        }

    }



}
