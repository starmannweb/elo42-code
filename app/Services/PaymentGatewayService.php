<?php

namespace App\Services;

/**
 * PaymentGatewayService — Integration stub for future payment gateway.
 *
 * Prepared for integration with providers like Stripe, PagSeguro, Asaas, etc.
 * Methods define the expected contract; implementations will be added
 * when a provider is selected.
 *
 * @package App\Services
 */
class PaymentGatewayService
{
    protected string $provider;
    protected array $config;

    public function __construct(string $provider = 'stripe')
    {
        $this->provider = $provider;
        $this->config = [
            'api_key'    => env('PAYMENT_API_KEY', ''),
            'secret_key' => env('PAYMENT_SECRET_KEY', ''),
            'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', ''),
            'sandbox'    => env('PAYMENT_SANDBOX', true),
        ];
    }

    /**
     * Create a subscription for an organization.
     */
    public function createSubscription(int $organizationId, string $planSlug, array $paymentData): array
    {
        // TODO: Implement when payment provider is selected
        return [
            'success' => false,
            'message' => 'Payment gateway not yet configured.',
            'subscription_id' => null,
        ];
    }

    /**
     * Cancel an existing subscription.
     */
    public function cancelSubscription(string $subscriptionId): array
    {
        // TODO: Implement
        return ['success' => false, 'message' => 'Payment gateway not yet configured.'];
    }

    /**
     * Process a single charge (e.g., donation, one-time payment).
     */
    public function charge(float $amount, string $description, array $paymentData): array
    {
        // TODO: Implement
        return [
            'success' => false,
            'message' => 'Payment gateway not yet configured.',
            'transaction_id' => null,
        ];
    }

    /**
     * Handle webhook callback from the payment provider.
     */
    public function handleWebhook(array $payload): array
    {
        // TODO: Implement webhook verification and event processing
        return ['handled' => false];
    }

    /**
     * Get subscription status.
     */
    public function getSubscriptionStatus(string $subscriptionId): ?string
    {
        // TODO: Implement
        return null;
    }
}
