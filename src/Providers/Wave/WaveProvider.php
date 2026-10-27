<?php

namespace Laravelsn\Westafpay\Providers\Wave;

class WaveProvider
{
    protected WaveClient $client;

    public function __construct()
    {
        $this->client = new WaveClient;
    }

    public function getBalance(?bool $include_subaccounts = null)
    {
        return $this->client->balance($include_subaccounts);
    }

    public function getTransactions(?string $date = null, ?bool $include_subaccounts = null)
    {
        return $this->client->transactions($date, $include_subaccounts);
    }

    public function paginateTransactions(?string $date = null, ?bool $include_subaccounts = null)
    {
        return $this->client->paginateTransactions($date, $include_subaccounts);
    }

    public function refund(string $transaction_id)
    {
        return $this->client->refund($transaction_id);
    }

    public function createCheckout(array $payload)
    {
        return $this->client->createCheckout($payload);
    }

    public function getCheckout(string $id)
    {
        return $this->client->getCheckout($id);
    }

    public function getCheckoutByTransactionId(string $transaction_id)
    {
        return $this->client->getCheckoutByTransactionId($transaction_id);
    }

    public function searchForCheckout(string $client_reference)
    {
        return $this->client->searchForCheckout($client_reference);
    }

    public function refundCheckout(string $transaction_id)
    {
        return $this->client->refundCheckout($transaction_id);
    }

    public function expireCheckout(string $transaction_id)
    {
        return $this->client->expireCheckout($transaction_id);
    }

    public function createPayout(array $payload)
    {
        return $this->client->createPayout($payload);
    }

    /**
     * Retrieves a single payout by id (identifier starting with pt-).
     *
     * @return array
     */
    public function getPayout(string $id)
    {
        return $this->client->getPayout($id);
    }

    /**
     * Retrieves a list of payouts based on the provided query parameters. Currently supports only search by client reference (client_reference).
     *
     * @return array
     */
    public function searchPayout(string $client_reference)
    {
        return $this->client->searchPayout($client_reference);
    }

    public function createPayoutBatch(array $payload)
    {
        return $this->client->createPayoutBatch($payload);
    }

    /**
     * Retrieves a payout batch by id (identifier starting with pb-).
     */
    public function getPayoutBatch(string $id)
    {
        return $this->client->getPayoutBatch($id);
    }

    /**
     * Reverses a payout by id (identifier starting with pt-).
     */
    public function reversePayout(string $id)
    {
        return $this->client->reversePayout($id);
    }

    public function getMerchant()
    {
        return $this->client->getMerchant();
    }

    public function createMerchant(array $payload)
    {
        return $this->client->createMerchant($payload);
    }

    public function getMerchantById(string $id)
    {
        return $this->client->getMerchantById($id);
    }

    public function updateMerchant(string $id, array $payload)
    {
        return $this->client->updateMerchant($id, $payload);
    }

    public function deleteMerchant(string $id)
    {
        return $this->client->deleteMerchant($id);
    }
}
