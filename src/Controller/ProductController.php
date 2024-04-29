<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Exception\PayloadException;
use App\Service\Merchant;
use App\Service\PayloadFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate_price')]
    public function calculatePrice(
        Request                $request,
        PayloadFactory         $payloadFactory,
        EntityManagerInterface $entityManager,
        Merchant               $merchant,
    ): Response
    {
        try {
            $purchasePayload = $payloadFactory->createCalculatePricePayload($request->getContent());
        } catch (PayloadException $payloadException) {

            $responseArray = ['error' => $payloadException->getMessage()];
            if ($payloadException->violations !== null) {
                foreach ($payloadException->violations as $violation) {
                    $responseArray['info'][] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
                }
            }

            return $this->json($responseArray, 400);
        }

        return $this->json(['price' => $merchant->calculatePrice(
            $entityManager->getRepository(Product::class)->find($purchasePayload->getProduct()),
            $purchasePayload->getTaxNumber(),
            $purchasePayload->getCouponCode() !== null ? $entityManager->getRepository(Coupon::class)->findByCode($purchasePayload->getCouponCode()) : null
        )]);
    }

    #[Route('/purchase', name: 'purchase')]
    public function purchase(
        Request                $request,
        PayloadFactory         $payloadFactory,
        EntityManagerInterface $entityManager,
        Merchant               $merchant,
    ): Response
    {
        $entityManager->beginTransaction();

        try {
            $purchasePayload = $payloadFactory->createPurchasePayload($request->getContent());
        } catch (PayloadException $payloadException) {

            $responseArray = ['error' => $payloadException->getMessage()];
            if ($payloadException->violations !== null) {
                foreach ($payloadException->violations as $violation) {
                    $responseArray['info'][] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
                }
            }

            $entityManager->rollback();
            return $this->json($responseArray, 400);
        }

        try {
            $merchant->buy(
                $entityManager->getRepository(Product::class)->find($purchasePayload->getProduct()),
                $purchasePayload->getTaxNumber(),
                $purchasePayload->getPaymentProcessor(),
                $purchasePayload->getCouponCode() !== null ? $entityManager->getRepository(Coupon::class)->findByCode($purchasePayload->getCouponCode()) : null,
            );
        } catch (\Exception $exception) {
            $entityManager->rollback();
            return $this->json('error', 400);
        }

        $entityManager->commit();

        return $this->json(['status' => 'ok']);
    }
}