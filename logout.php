<?php

include('include.php');

session_reset();
session_destroy();

$actualurl = "login.php";
?>
    <script>
        window.location = "<?php echo $actualurl; ?>";
    </script>
    <?php

?>