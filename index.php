<?php 
    ob_start();    
    session_start();

    $pageTitle  = 'Home';

    include 'init.php';
?>
<div class="container">
    <div class="row">
        <?php 
            $allItems = getAllFrom('*', 'items', 'WHERE Approve = 1', '', 'item_ID', 'DESC');
            foreach ($allItems as $item) {
                echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price">$' . $item['Price'] . '</span>';
                        echo '<img class="img-responsive" src="man.png" alt="item-image">';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid='. $item['item_ID'] .'" target="_blank">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        ?>
    </div>
</div>


<?php 



    include $templates . 'footer.php'; 
    ob_end_flush();
?>