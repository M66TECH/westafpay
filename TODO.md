## Implémenter l'[API de réconciliation](https://docs.wave.com/balance-api#balance-amp-reconciliation-api) de Wave

- implémenter l'endpoint balance
    - [x] Mettre en place l'appel API avec Saloon pour l'endpoint balance
    - [x] Ajouter `include_subaccounts` comme paramètre de requête

- implémenter l'endpoint de liste des transactions
    - [x] Mettre en place l'appel API avec Saloon
    - [x] Ajouter les paramètres de requête date, after, include_subaccounts
    - [x] Gérer la pagination (voir `paginateTransactions()`)

- implémenter l'endpoint de remboursement
    - [x] Mettre en place l'appel API avec Saloon (transaction_id)

## Prochaines étapes

- [ ] Webhooks : vérifier l'en-tête `Wave-Signature` des événements entrants
- [x] Signature des requêtes (activer avec `WAVE_SIGNING_SECRET`)
- [ ] Ajouter des tests pour les endpoints de l'API Merchant
- [ ] Ajouter le support d'un autre fournisseur de paiement d'Afrique de l'Ouest
