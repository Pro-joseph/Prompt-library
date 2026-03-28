<?php
// Static demo version - no dynamic logic or database required
include ("header.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Prompt - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>


    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            <!-- Page Header -->
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="prompts.php" class="text-decoration-none">Prompts</a></li>
                        <li class="breadcrumb-item active">Nouveau</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold mb-1">Créer un nouveau prompt</h1>
                <p class="text-muted mb-0">Partagez votre prompt performant avec l'équipe</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form action="" method="POST" id="addPromptForm">
                                <!-- Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-medium">Titre du prompt <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Ex: Générateur d'API REST" required>
                                    <div class="form-text">Un titre clair et descriptif pour identifier facilement ce prompt</div>
                                </div>

                                <!-- Category -->
                                <div class="mb-4">
                                    <label for="category" class="form-label fw-medium">Catégorie <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg" id="category" name="category" required>
                                        <option value="" selected disabled>Sélectionnez une catégorie</option>
                                        <option value="1">Code</option>
                                        <option value="2">SQL</option>
                                        <option value="3">DevOps</option>
                                        <option value="4">Marketing</option>
                                        <option value="5">Documentation</option>
                                        <option value="6">Testing</option>
                                        <option value="7">Design</option>
                                        <option value="8">Autre</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-medium">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Décrivez brièvement ce que fait ce prompt et quand l'utiliser..."></textarea>
                                </div>

                                <!-- Prompt Content -->
                                <div class="mb-4">
                                    <label for="content" class="form-label fw-medium">Contenu du prompt <span class="text-danger">*</span></label>
                                    <textarea class="form-control font-monospace" id="content" name="content" rows="10" placeholder="Tu es un expert [DOMAINE]. [INSTRUCTIONS DETAILLEES]..." required></textarea>
                                    <div class="form-text">
                                        <i class="bi bi-lightbulb me-1"></i>
                                        Utilisez [VARIABLE] pour indiquer les parties à personnaliser
                                    </div>
                                </div>

                                <!-- Variables -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Variables détectées</label>
                                    <div id="variablesContainer" class="d-flex flex-wrap gap-2">
                                        <span class="text-muted small">Ajoutez des variables avec la syntaxe [NOM] dans votre prompt</span>
                                    </div>
                                </div>

                                <!-- Tags -->
                                <div class="mb-4">
                                    <label for="tags" class="form-label fw-medium">Tags</label>
                                    <input type="text" class="form-control" id="tags" name="tags" placeholder="api, rest, node, express (séparés par des virgules)">
                                    <div class="form-text">Ajoutez des tags pour faciliter la recherche</div>
                                </div>

                                <!-- Visibility -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Visibilité</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="visibility" id="visibilityPublic" value="public" checked>
                                            <label class="form-check-label" for="visibilityPublic">
                                                <i class="bi bi-globe me-1"></i>Public (visible par toute l'équipe)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="visibility" id="visibilityPrivate" value="private">
                                            <label class="form-check-label" for="visibilityPrivate">
                                                <i class="bi bi-lock me-1"></i>Privé (visible par moi uniquement)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Actions -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="prompts.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg me-2"></i>Annuler
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" id="previewBtn">
                                            <i class="bi bi-eye me-2"></i>Aperçu
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-lg me-2"></i>Enregistrer le prompt
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Tips -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white border-0">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-lightbulb me-2"></i>Conseils pour un bon prompt</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3 d-flex">
                                    <span class="badge bg-primary rounded-circle me-2">1</span>
                                    <span class="small"><strong>Définissez un rôle</strong> - "Tu es un expert en..."</span>
                                </li>
                                <li class="mb-3 d-flex">
                                    <span class="badge bg-primary rounded-circle me-2">2</span>
                                    <span class="small"><strong>Soyez spécifique</strong> - Décrivez le contexte et le résultat attendu</span>
                                </li>
                                <li class="mb-3 d-flex">
                                    <span class="badge bg-primary rounded-circle me-2">3</span>
                                    <span class="small"><strong>Utilisez des variables</strong> - [VARIABLE] pour les parties personnalisables</span>
                                </li>
                                <li class="mb-3 d-flex">
                                    <span class="badge bg-primary rounded-circle me-2">4</span>
                                    <span class="small"><strong>Donnez des exemples</strong> - Montrez le format de sortie souhaité</span>
                                </li>
                                <li class="d-flex">
                                    <span class="badge bg-primary rounded-circle me-2">5</span>
                                    <span class="small"><strong>Testez avant de partager</strong> - Vérifiez que le prompt donne des résultats cohérents</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-code-slash me-2"></i>Exemple de structure</h6>
                        </div>
                        <div class="card-body">
                            <pre class="bg-dark text-light p-3 rounded small mb-0"><code>Tu es un [ROLE] expert en [DOMAINE].

Contexte :
[DESCRIPTION DU CONTEXTE]

Tâche :
[DESCRIPTION DE LA TACHE]

Contraintes :
- [CONTRAINTE 1]
- [CONTRAINTE 2]

Format de sortie :
[FORMAT ATTENDU]

Exemple :
[EXEMPLE SI NECESSAIRE]
</code>
</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Aperçu du prompt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="badge bg-primary-subtle text-primary mb-2" id="previewCategory">Catégorie</span>
                        <h4 class="fw-bold" id="previewTitle">Titre du prompt</h4>
                        <p class="text-muted" id="previewDescription">Description</p>
                    </div>
                    <div class="bg-light rounded p-3">
                        <pre class="mb-0"><code id="previewContent">Contenu du prompt</code></pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-light border-top py-3 mt-auto">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted small mb-0">&copy; 2026 DevGenius Solutions. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="#" class="text-muted small text-decoration-none me-3">Aide</a>
                    <a href="#" class="text-muted small text-decoration-none me-3">Confidentialité</a>
                    <a href="#" class="text-muted small text-decoration-none">Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Detect variables in prompt content
        const contentTextarea = document.getElementById('content');
        const variablesContainer = document.getElementById('variablesContainer');

        contentTextarea.addEventListener('input', function() {
            const content = this.value;
            const variables = content.match(/\[([A-Z_]+)\]/g);
            
            if (variables && variables.length > 0) {
                const uniqueVars = [...new Set(variables)];
                variablesContainer.innerHTML = uniqueVars.map(v => 
                    `<span class="badge bg-secondary">${v}</span>`
                ).join('');
            } else {
                variablesContainer.innerHTML = '<span class="text-muted small">Ajoutez des variables avec la syntaxe [NOM] dans votre prompt</span>';
            }
        });

        // Preview functionality
        document.getElementById('previewBtn').addEventListener('click', function() {
            const title = document.getElementById('title').value || 'Titre du prompt';
            const category = document.getElementById('category');
            const categoryText = category.options[category.selectedIndex]?.text || 'Catégorie';
            const description = document.getElementById('description').value || 'Aucune description';
            const content = document.getElementById('content').value || 'Aucun contenu';

            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewCategory').textContent = categoryText;
            document.getElementById('previewDescription').textContent = description;
            document.getElementById('previewContent').textContent = content;

            new bootstrap.Modal(document.getElementById('previewModal')).show();
        });
    </script>
</body>
</html>
