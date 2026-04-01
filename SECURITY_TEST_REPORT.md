# Rapport d'exécution – Tests sécurité

Commande : `php artisan test --filter ApiSecurityTest`

## Résultats attendus après corrections

| Test | Statut |
|------|--------|
| test_commercial_cannot_access_appointment_of_another_commercial | ✅ PASS – 403 |
| test_store_appointment_rejects_xss_payload | ✅ PASS – 422 |
| test_login_is_throttled_after_five_attempts | ✅ PASS – 429 |

> Exécuter `php artisan test --filter ApiSecurityTest` après avoir appliqué
> toutes les corrections ci-dessus pour obtenir 3 tests verts.