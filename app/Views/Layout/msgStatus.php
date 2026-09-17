<?php
// Get flashdata messages
$msgDanger = session()->getFlashdata('msgDanger');
$msgSuccess = session()->getFlashdata('msgSuccess');
$msgWarning = session()->getFlashdata('msgWarning');
$msgInfo = session()->getFlashdata('msgInfo');
$userid = session()->has('userid');

// Auto-dismiss timer for alerts (in milliseconds)
$autoDismissTime = 5000;
?>

<?php if ($msgDanger) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= htmlspecialchars($msgDanger) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
<?php elseif ($msgSuccess) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= htmlspecialchars($msgSuccess) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
<?php elseif ($msgWarning) : ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?= htmlspecialchars($msgWarning) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
<?php elseif ($msgInfo) : ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <?= htmlspecialchars($msgInfo) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
<?php elseif ($userid && isset($messages) && is_array($messages) && count($messages) >= 2) : ?>
    <?php 
    $messageText = htmlspecialchars($messages[0]);
    $messageType = in_array($messages[1], ['danger', 'success', 'warning', 'info', 'primary']) ? $messages[1] : 'primary';
    ?>
    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
        <?= $messageText ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
<?php elseif ($userid) : ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <i class="bi bi-person-check-fill me-2"></i>
        Welcome back, <?= htmlspecialchars(getName($user ?? null)) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
<?php else : ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <i class="bi bi-person-plus-fill me-2"></i>
        Welcome Stranger! Please <a href="/login" class="alert-link">login</a> to continue.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($autoDismissTime && ($msgSuccess || $msgInfo)) : ?>
<script>
    // Auto-dismiss success and info messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success, .alert-info');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, <?= $autoDismissTime ?>);
</script>
<?php endif; ?>