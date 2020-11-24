<?php
    if(isset($_SESSION['toastMessage']) && empty($_SESSION['toastMessage']) == false)
    {
        echo '
        <script id="toastCall" type="text/javascript">
            showSnackbar("'.$_SESSION['toastMessage'].'");
        </script>';
        unset($_SESSION['toastMessage']);
    }
?>