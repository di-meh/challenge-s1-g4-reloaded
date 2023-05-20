<?php

namespace App\Service;

use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;

class StripeService {

    private StripeClient $stripeClient;

    public function __construct(
        private UserRepository $userRepository,
        private AnnoncesRepository $annoncesRepository,
        private EntityManagerInterface $entityManager) {
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
