## Implement wave [reconciliation api](https://docs.wave.com/balance-api#balance-amp-reconciliation-api)

- implement balance endpoint
    - [x] Setup API call using Saloon for balance endpoint
    - [x] Add `include_subaccounts` as a query parameter

- implement transaction list endpoint
    - [x] setup api call using saloon
    - [x] add query parameters date, after, include_subaccounts
    - [x] handle the pagination (see `paginateTransactions()`)

- implement refund endpoint
    - [x] setup api call using saloon (transaction_id)

## Next steps

- [ ] Webhooks: verify the `Wave-Signature` header of incoming events
- [ ] Request signing for every request (done, enable with `WAVE_SIGNING_SECRET`)
- [ ] Add tests for the Merchant API endpoints
- [ ] Support another West African payment provider
