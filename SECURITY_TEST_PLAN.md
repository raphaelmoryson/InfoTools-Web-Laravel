# Plan de tests sécurité – InfoTools API

| # | Scénario | Route | Méthode | Résultat attendu |
|---|----------|-------|---------|-----------------|
| 1 | IDOR : commercial A accède au RDV du commercial B | GET /api/appointments-api/{id} | Token commercial A | 403 Forbidden |
| 2 | Validation XSS : payload malformé + balise script | POST /api/appointments-api | Token valide | 422 Unprocessable |
| 3 | Throttling : 6e tentative de login en 1 min | POST /api/login | Aucun token | 429 Too Many Requests |
| 4 | Audit log : création d'un RDV génère une entrée | POST /api/appointments-api | Token valide | Ligne INSERT dans audit_logs |