<?php

namespace App\DependencyInjection;

use App\Service\Merchant;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PaymentProvidersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $paymentsConfig = $container->getParameter('payment');

        $paymentProcessors = [];

        foreach ($paymentsConfig as $name => $paymentConfig) {
            $paymentProcessors[$name] = [
                Merchant::SERVICE_CONFIG_STRING => new Reference($paymentConfig[Merchant::SERVICE_CONFIG_STRING]),
                Merchant::METHOD_CONFIG_STRING => $paymentConfig[Merchant::METHOD_CONFIG_STRING]
            ];
        }

        $purchaseProcessor = $container->getDefinition(Merchant::class);
        $purchaseProcessor->addArgument($paymentProcessors);
    }
}