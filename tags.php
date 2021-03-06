<?php 
    session_start();
    include 'init.php';
?>
<div class="container">
    <div class="row">
        <?php
            if (isset($_GET['name'])) {       
                $tag = $_GET['name'];
                echo "<h1 class='text-center'>" . $tag . "</h1>";
                
                $itemsTag = getAllFrom('*', 'items', "WHERE tags like '%$tag%'", 'AND Approve = 1', 'item_ID');
                foreach ($itemsTag as $item) {
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
            } else {
                echo "No items to show";
            }
        ?>
    </div>
</div>

<?php include $templates . 'footer.php'; ?>