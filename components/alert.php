<?php
function showAlert()
{
    if (isset($_SESSION['alert'])) {
        $type = $_SESSION['alert']['type'] ?? 'info';
        $message = $_SESSION['alert']['message'] ?? '';

        echo '<div class="custom-alert alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
        echo  $message;
        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        echo '</div>';

        // Remove alert after showing it once
        unset($_SESSION['alert']);
    }
}
?>