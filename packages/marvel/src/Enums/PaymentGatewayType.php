<?php


namespace Marvel\Enums;

use BenSampo\Enum\Enum;

/**
 * Class RoleType
 * @package App\Enums
 */
final class PaymentGatewayType extends Enum
{
    public const STRIPE = 'STRIPE';
    public const CASH_ON_DELIVERY = 'CASH_ON_DELIVERY';
    public const CASH = 'CASH';
    public const FULL_WALLET_PAYMENT = 'FULL_WALLET_PAYMENT';
    public const PAYPAL = 'PAYPAL';
    public const RAZORPAY = 'RAZORPAY';
    public const MOLLIE = 'MOLLIE';
    public const PAYSTACK = 'PAYSTACK';
    public const ETISALAT = 'ETISALAT';
}
