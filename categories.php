<?php 
    session_start();
    include 'init.php';
?>
<div class="container">
    <h1 class="text-center">Show Categories Items</h1>
    <div class="row">
        <?php
            $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
            $allItems = getAllFrom('*', 'items', "WHERE Cat_ID = {$category}", 'AND Approve = 1', 'item_ID');
            foreach ($allItems as $item) {
                echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price">$' . $item['Price'] . '</span>';
                        echo '<img class="item-image img-responsive" src="./admin/uploads/avatars/' . $item['avatar'] .'" alt="item-image">';
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

<?php include $templates . 'footer.php'; ?>