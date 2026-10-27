# Contribuer à Westafpay

Merci de votre intérêt pour Westafpay. Ce document explique comment démarrer.

## Prérequis

- PHP **8.4** ou supérieur
- [Composer](https://getcomposer.org)

## Installation locale

```bash
git clone https://github.com/laravel-sn/westafpay.git
cd westafpay
composer install
```

## Commandes utiles

```bash
composer test         # Lance la suite de tests Pest
composer analyse      # Lance PHPStan (larastan)
composer format       # Lance Pint
```

Assurez-vous que ces trois commandes passent avant d'ouvrir une pull request.

## Déroulement

1. Forkez le dépôt.
2. Créez une branche depuis `main` : `git checkout -b fix/ma-correction`.
3. Faites votre modification, ajoutez les tests correspondants et mettez à jour la documentation si besoin.
4. Lancez `composer format`, `composer analyse` et `composer test`.
5. Faites un commit avec un message clair, par exemple `fix: utilise l'endpoint checkout au singulier`.
6. Poussez votre branche et ouvrez une pull request vers `main`.

## Où contribuer

Consultez [TODO.md](TODO.md) pour les fonctionnalités manquantes connues et les issues ouvertes.
Quelques bons points de départ :

- Ajouter des tests pour les endpoints qui n'en ont pas encore.
- Implémenter une nouvelle API Wave (par exemple les webhooks).
- Améliorer la documentation.
- Ajouter le support d'un autre fournisseur de paiement d'Afrique de l'Ouest.

## Ajouter un nouvel endpoint Wave

Les requêtes se trouvent dans `src/Http/Integrations/Wave/Requests/<Api>/`. Chaque requête est un
`Request` Saloon qui a seulement besoin d'un `resolveEndpoint()` (et éventuellement de
`defaultQuery()` / `defaultBody()`). Exposez-la ensuite depuis `WaveClient`, `WaveProvider`, et
ajoutez un test dans `tests/Http/Integrations/Wave/Requests`.

Vérifiez toujours l'endpoint dans la [documentation officielle de Wave](https://docs.wave.com).

## Style de code

- Suivez les conventions existantes (Pint / preset Laravel).
- N'utilisez pas d'aides de débogage comme `dd`, `dump` ou `ray` (vérifié par un test d'architecture).
- Gardez des pull requests ciblées : un seul sujet par pull request.
