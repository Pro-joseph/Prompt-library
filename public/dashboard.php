<?php
// Static demo version - no dynamic logic or database required
include ("header.php");
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>


    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-banner p-4 rounded-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h3 fw-bold text-white mb-2">Bienvenue, Demo User!</h1>
                                <p class="text-white-50 mb-0">Gérez et partagez vos prompts performants avec votre équipe.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="add-prompt.php" class="btn btn-light">
                                    <i class="bi bi-plus-lg me-2"></i>Nouveau Prompt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary-subtle text-primary rounded-3 p-3 me-3">
                                    <i class="bi bi-file-text fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Total Prompts</p>
                                    <h3 class="fw-bold mb-0">124</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success-subtle text-success rounded-3 p-3 me-3">
                                    <i class="bi bi-person-plus fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Mes Prompts</p>
                                    <h3 class="fw-bold mb-0">12</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-3 me-3">
                                    <i class="bi bi-tags fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Catégories</p>
                                    <h3 class="fw-bold mb-0">6</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info-subtle text-info rounded-3 p-3 me-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Contributeurs</p>
                                    <h3 class="fw-bold mb-0">8</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recent Prompts -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-bold">My Prompts</h5>
                                <a href="prompts.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 ps-4">Titre</th>
                                            <th class="border-0">Catégorie</th>
                                            <th class="border-0">Auteur</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0 text-end pe-4 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Refactor Python Loop</td>
                                            <td><span class="badge bg-secondary">Code</span></td>
                                            <td>Demo User</td>
                                            <td>27 Mar 2026</td>
                                            <td>
                                                <a href="view-prompt.php?id=1" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit-prompt.php?id=1" class="btn btn-sm btn-outline-success" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SQL Optimization Query</td>
                                            <td><span class="badge bg-secondary">SQL</span></td>
                                            <td>Alex Dev</td>
                                            <td>25 Mar 2026</td>
                                            <td>
                                                <a href="view-prompt.php?id=2" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit-prompt.php?id=2" class="btn btn-sm btn-outline-success" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Categories -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold">Catégories</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="prompts.php?search=&category=1&author=" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-code-slash me-1"></i>Code <span class="badge bg-primary ms-1"></span>
                                </a>
                                <a href="prompts.php?search=&category=4&author=" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-database me-1"></i>SQL <span class="badge bg-success ms-1"></span>
                                </a>
                                <a href="prompts.php?search=&category=3&author=" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-gear me-1"></i>DevOps <span class="badge bg-info ms-1"></span>
                                </a>
                                <a href="prompts.php?search=&category=2&author=" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-megaphone me-1"></i>Marketing <span class="badge bg-danger ms-1"></span>
                                </a>
                                <a href="prompts.php?search=&category=1&author=" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-file-text me-1"></i>Docs <span class="badge bg-warning ms-1"></span>
                                </a>
                                <a href="prompts.php?search=&category=5&author=" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-bug me-1"></i>Testing <span class="badge bg-secondary ms-1"></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Top Contributors -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                                <h5 class="card-title mb-0 fw-bold">Top Contributeurs</h5>
                        </div>
                            <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 bg-warning text-white d-flex align-items-center justify-content-center rounded-circle">
                                    DU
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium">Demo User</p>
                                    <small class="text-muted">developer</small>
                                </div>
                            </div>
                            <span class="badge bg-warning rounded-pill">12</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    AD
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium">Alex Dev</p>
                                    <small class="text-muted">admin</small>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill">45</span>
                        </li>
                    </ul>
                </div>
                    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

</body>
</html>
