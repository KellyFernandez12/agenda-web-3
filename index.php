<?php
declare(strict_types=1);

function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$eventos = [];
$errorGeneral = '';

try {
    require __DIR__ . '/conexion.php';
    $resultado = $mysqli->query('SELECT id, titulo, fecha, hora, categoria, descripcion FROM eventos ORDER BY fecha ASC, hora ASC, id DESC');
    $eventos = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();
    $mysqli->close();
} catch (Throwable $e) {
    if (isset($mysqli) && $mysqli instanceof mysqli) {
        $mysqli->close();
    }
    $errorGeneral = 'No se pudieron cargar los eventos. Revisa la configuración de la base de datos.';
}

$etiquetasCategoria = [
    'trabajo' => 'Trabajo',
    'personal' => 'Personal',
    'estudio' => 'Estudio',
    'ocio' => 'Ocio',
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgendaWeb | Mis eventos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php">AgendaWeb</a>
    <nav aria-label="Navegación principal"><a href="registrar.php">Registrar evento</a></nav>
</header>
<main class="container">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Tu espacio organizado</p>
            <h1>Mis eventos</h1>
            <p class="intro">Consulta las actividades guardadas en tu agenda.</p>
        </div>
        <a class="button" href="registrar.php">+ Nuevo evento</a>
    </div>

    <?php if (isset($_GET['ok']) && $_GET['ok'] === '1'): ?>
        <div class="alert alert-success" role="status">Evento guardado correctamente.</div>
    <?php endif; ?>

    <?php if ($errorGeneral !== ''): ?>
        <div class="alert alert-error" role="alert"><?= h($errorGeneral) ?></div>
    <?php elseif (!$eventos): ?>
        <section class="card empty-state">
            <h2>Aún no hay eventos</h2>
            <p>Agrega tu primer evento para empezar a organizarte.</p>
            <a class="button" href="registrar.php">Registrar evento</a>
        </section>
    <?php else: ?>
        <section class="event-list" aria-label="Lista de eventos">
            <?php foreach ($eventos as $evento): ?>
                <article class="card event-card">
                    <div class="event-meta">
                        <span class="badge"><?= h($etiquetasCategoria[$evento['categoria']] ?? 'Otra') ?></span>
                        <time datetime="<?= h($evento['fecha']) ?>"><?= h(date('d/m/Y', strtotime($evento['fecha']))) ?></time>
                        <?php if ($evento['hora'] !== null && $evento['hora'] !== ''): ?>
                            <span><?= h(substr($evento['hora'], 0, 5)) ?> h</span>
                        <?php endif; ?>
                    </div>
                    <h2><?= h($evento['titulo']) ?></h2>
                    <?php if ($evento['descripcion'] !== null && $evento['descripcion'] !== ''): ?>
                        <p class="event-description"><?= nl2br(h($evento['descripcion'])) ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
<footer class="site-footer">AgendaWeb · Práctica P3</footer>
</body>
</html>
