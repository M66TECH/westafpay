# Changelog

Toutes les modifications notables de `westafpay` sont documentées dans ce fichier.

## Non publié

### Corrigé

- Publication du fichier de configuration et enregistrement de la commande `westafpay` via `spatie/laravel-package-tools`.
- Correction de l'URL de base par défaut de Wave (`https://api.wave.com.` contenait un point final en trop).
- Utilisation des bons endpoints Checkout de Wave (`/v1/checkout/sessions` au lieu de `/v1/checkouts/sessions`).
- Utilisation du bon endpoint de recherche de payout (`/v1/payouts/search` au lieu de `/v1/payout/search`).
- Remplacement de `reservePayout()` (aucun endpoint `reserve` n'existe chez Wave) par `reversePayout()` (`POST /v1/payout/:id/reverse`).
- Renommage du namespace `DataTransfertObjects` en `DataTransferObjects`.

### Ajouté

- Signature des requêtes : envoi automatique de l'en-tête `Wave-Signature` lorsque `WAVE_SIGNING_SECRET` est défini.
- Pagination des transactions via `paginateTransactions()`, et prise en charge de `first` dans `getTransactions()`.
- Nouvelles clés de configuration optionnelles `signing_secret` et `webhook_secret`.
- Une suite de tests couvrant le connecteur, le client et les requêtes Wave.
- `CONTRIBUTING.md` et un `README.md` plus complet.
