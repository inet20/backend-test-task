<?php

namespace App\Service;

use App\Dto\CalculatePricePayload;
use App\Dto\PurchasePayload;
use App\Exception\PayloadException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PayloadFactory
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    )
    {
    }

    /**
     * @throws PayloadException
     */
    public function createPurchasePayload(string $jsonData): PurchasePayload
    {
        return $this->CreatePayload($jsonData, PurchasePayload::class);
    }

    /**
     * @throws PayloadException
     */
    public function createCalculatePricePayload(string $jsonData): CalculatePricePayload
    {
        return $this->CreatePayload($jsonData, CalculatePricePayload::class);
    }

    /**
     * @throws PayloadException
     */
    private function CreatePayload(string $jsonData, string $payloadClass)
    {
        $violations = new ConstraintViolationList();
        try {
            $payload = $this->serializer->deserialize($jsonData, $payloadClass, 'json', [
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            ]);
        } catch (PartialDenormalizationException $e) {
            /** @var NotNormalizableValueException $exception */
            foreach ($e->getErrors() as $exception) {
                $message = sprintf('The type must be one of "%s" ("%s" given).', implode(', ', $exception->getExpectedTypes()), $exception->getCurrentType());
                $parameters = [];
                if ($exception->canUseMessageForUser()) {
                    $parameters['hint'] = $exception->getMessage();
                }
                $violations->add(new ConstraintViolation($message, '', $parameters, null, $exception->getPath(), null));
            }

            throw new PayloadException("Invalid data passed.", $violations);
        } catch (\Throwable $exception) {
            throw new PayloadException("Invalid data passed. Please check payload.");
        }

        $errors = $this->validator->validate($payload);

        if (\count($errors) > 0) {
            throw new PayloadException("Incorrect data passed.", $errors);
        }

        return $payload;
    }
}