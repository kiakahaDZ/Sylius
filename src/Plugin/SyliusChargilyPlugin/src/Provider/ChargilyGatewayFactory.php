<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

final class ChargilyGatewayFactory
{
    public const FACTORY_NAME = 'chargily_pay';

    public const METHOD_EDAHABIA = 'edahabia';

    public const METHOD_CIB = 'cib';

    public function getName(): string
    {
        return self::FACTORY_NAME;
    }

    /**
     * @return array<string, string>
     */
    public function getSupportedPaymentMethods(): array
    {
        return [
            self::METHOD_CIB => 'sylius_chargily.form.payment_method.cib',
            self::METHOD_EDAHABIA => 'sylius_chargily.form.payment_method.edahabia',
        ];
    }
}
