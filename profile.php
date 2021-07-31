<?php 
    session_start();
    $pageTitle  = 'Profile';
    include 'init.php';

?>

<h1 class="text-center"><?php echo $_SESSION['user']; ?> Profile</h1>

<div class="informations block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                Name : Moahemd
            </div>

        </div>
    </div>
</div>
<div class="ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Ads</div>
            <div class="panel-body">
                Ads Name : one 
            </div>

        </div>
    </div>
</div>
<div class="comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                Test Comments
            </div>

        </div>
    </div>
</div>

<?php

    include $templates . 'footer.php'; 

?>