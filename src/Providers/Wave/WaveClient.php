<?php

namespace Laravelsn\Westafpay\Providers\Wave;

use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\CreateCheckout as CreateCheckoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\ExpireCheckout as ExpireCheckoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\GetCheckout as GetCheckoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\GetCheckoutByTransactionId as GetCheckoutByTransactionIdRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\RefundCheckout as RefundCheckoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\SearchForCheckout as SearchForCheckoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Merchant\CreateMerchant as CreateMerchantRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Merchant\DeleteMerchant as DeleteMerchantRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Merchant\GetMerchant as GetMerchantRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Merchant\GetMerchantById as GetMerchantByIdRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Merchant\UpdateMerchant as UpdateMerchantRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\CreatePayout as CreatePayoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\GetPayout as GetPayoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\GetPayoutBatch as GetPayoutBatchRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\PayoutBatch as PayoutBatchRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\ReversePayout as ReversePayoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\SearchPayout as SearchPayoutRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Balance as BalanceRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Refund as RefundRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Transaction as TransactionRequest;
use Laravelsn\Westafpay\Http\Integrations\Wave\WaveConnector;
use Saloon\PaginationPlugin\Paginator;

class WaveClient
{
    protected WaveConnector $connector;

    public function __construct()
    {
        $this->connector = new WaveConnector;
    }

    public function balance(?bool $include_subaccounts = null)
    {
        $balanceRequest = new BalanceRequest;

        if ($include_subaccounts !== null) {
            $balanceRequest->query()->add('include_subaccounts', $include_subaccounts ? 'true' : 'false');
        }

        $response = $this->connector->send($balanceRequest);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function transactions(?string $date = null, ?bool $include_subaccounts = null, ?int $first = null)
    {
        $transactionRequest = new TransactionRequest($date, $include_subaccounts, $first);

        $response = $this->connector->send($transactionRequest);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * Lazily paginate through every transaction returned by the Wave API.
     */
    public function paginateTransactions(?string $date = null, ?bool $include_subaccounts = null, ?int $first = null): Paginator
    {
        return $this->connector->paginate(new TransactionRequest($date, $include_subaccounts, $first));
    }

    public function refund(string $transaction_id)
    {
        $refundRequest = new RefundRequest($transaction_id);

        $response = $this->connector->send($refundRequest);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * Create a checkout session
     *
     * @param  array  $payload
     *                          [
     *                          'amount' => 100,
     *                          'currency' => 'XOF',
     *                          'error_url' => 'https://example.com/error',
     *                          'success_url' => 'https://example.com/success'
     *                          ]
     */
    public function createCheckout(array $payload)
    {
        $response = $this->connector->send(new CreateCheckoutRequest($payload));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function getCheckout(string $id)
    {
        $response = $this->connector->send(new GetCheckoutRequest($id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function getCheckoutByTransactionId(string $transaction_id)
    {
        $response = $this->connector->send(new GetCheckoutByTransactionIdRequest($transaction_id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function searchForCheckout(string $client_reference)
    {
        $response = $this->connector->send(new SearchForCheckoutRequest($client_reference));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function refundCheckout(string $transaction_id)
    {
        $response = $this->connector->send(new RefundCheckoutRequest($transaction_id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function expireCheckout(string $transaction_id)
    {
        $response = $this->connector->send(new ExpireCheckoutRequest($transaction_id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * Create a payout
     *
     * @param  array  $payload
     *                          [
     *                          'currency' => 'XOF',
     *                          'receive_amount' => '500',
     *                          'name' => 'Fatou Ndiaye',
     *                          'mobile' => '+221555110219',
     *                          ]
     */
    public function createPayout(array $payload)
    {
        $response = $this->connector->send(new CreatePayoutRequest($payload));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * Get a payout by the id of the payout
     */
    public function getPayout(string $id)
    {
        $response = $this->connector->send(new GetPayoutRequest($id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function searchPayout(string $client_reference)
    {
        $response = $this->connector->send(new SearchPayoutRequest($client_reference));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function createPayoutBatch(array $payload)
    {
        $response = $this->connector->send(new PayoutBatchRequest($payload));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function getPayoutBatch(string $id)
    {
        $response = $this->connector->send(new GetPayoutBatchRequest($id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * Reverse a payout by id (identifier starting with pt-).
     */
    public function reversePayout(string $id)
    {
        $response = $this->connector->send(new ReversePayoutRequest($id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function getMerchant()
    {
        $response = $this->connector->send(new GetMerchantRequest);
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function createMerchant(array $payload)
    {
        $response = $this->connector->send(new CreateMerchantRequest($payload));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function getMerchantById(string $id)
    {
        $response = $this->connector->send(new GetMerchantByIdRequest($id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function updateMerchant(string $id, array $payload)
    {
        $response = $this->connector->send(new UpdateMerchantRequest($id, $payload));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    public function deleteMerchant(string $id)
    {
        $response = $this->connector->send(new DeleteMerchantRequest($id));
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }
}
