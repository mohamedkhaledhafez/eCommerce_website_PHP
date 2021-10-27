<?php 


        /**
        ** Get All Function v2.0
        ** [Function To Get All Records From any table 
        */

        function getAllFrom($field, $tableName, $where = NULL, $and = NULL, $orderField, $ordering = "DESC") {
            
            global $con;

            $getAll = $con->prepare("SELECT $field FROM $tableName $where $and ORDER BY $orderField $ordering");

            $getAll->execute();
            
            $all = $getAll->fetchAll();

            return $all;
        }
    
        /**
        ** Get Categories Function v2.0
        ** [Function To Get Categories From Database 
        */

        function getCat() {
            
            global $con;

            $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

            $getCat->execute();
            
            $cats = $getCat->fetchAll();

            return $cats;
        }


        /**
        ** Get Advertise Items Functions v2.0
        ** [Function To Get Advertise Items From Database 
        */
/* 
        function getItems($where, $value, $approve = null) {
            
            global $con;

            $sql = $approve == null ? 'AND Approve = 1' : '';
        
            $getItem = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY item_ID DESC");

            $getItem->execute(array($value));
            
            $items = $getItem->fetchAll();

            return $items;
        }
 */

        /**
         * Check if the user is not activated
         * Function to check the register status of the user
         */

        function checkUserStatus($user) {
            global $con;

            $stmtx = $con->prepare("SELECT
                                        UserName, RegisterStatus 
                                    FROM 
                                        users 
                                    Where 
                                        UserName = ? 
                                    AND 
                                        RegisterStatus = 0");
            $stmtx->execute(array($user));
            $status = $stmtx->rowCount();
            return $status; 
        }






    /**
     * Title function that echo the page title in case the page has a variable  $pageTitle 
     * and echo default title for other pages
     */

     function echoTitle() {

        global $pageTitle;

        if (isset($pageTitle)) {
            echo $pageTitle;
        } else {
            echo "Default";
        }
     }


     /*
      * Home Redirect Function
      * [This Function accept parameters] 
      * $theMsg   = Echo a message [Error | Success | Warning | any message]
      * $url      = The Link You Want To Redirect To
      * $seconds  = Seconds before Redircting to home page
      */


      function redirectHome ($theMsg, $url = null, $seconds = 3) {

        if ($url === null) {

            $url  = 'index.php';
            $link = 'Home';
        } else {

            if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {

                $url  = $_SERVER['HTTP_REFERER'];
                $link = "Previous"; 
            } else {

                $url = 'index.php';
                $link = "Home";
            }

        }

        echo $theMsg;

        echo "<div class='alert alert-info'>You will be redirected to $link page After $seconds seconds</div>";

        // Redirect to Home page (index page) => [url=index.php]
        header("refresh:$seconds;url=$url");

        // To exit and dont execute any code after header()
        exit();

      }


      /**
       * Function to check if items is already exist in database or not to add them if not exist
       * [This Function accept parameters]
       * $select = The Items To Select [ex: user, item, category] from >>> table ($from)
       * $from   = The Table To Select From [ex: users, items, categories]
       * $value  = The Value Of Select 
       */

       function checkItem($select, $from, $value) {
           
            global $con;

            $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

            $statement->execute(array($value));

            $count = $statement->rowCount();

            return $count;

       }


       /**
        * Count Number Of Items 
        * [Function To Count The Number Of Items Rows]
        * $item  = The items to count
        * $table = The table to choose data from it 
        */

       function countItems($item, $table) {

            global $con;
            
            $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

            $stmt2->execute();

            return $stmt2->fetchColumn();


       }



       /**
        ** Get The Latest Records
        ** [Function To Get Latest Items/Users/Categories/Comments... From Database 
        ** $select = Field to select
        ** $table  = The table to choose user's data from it
        ** $order  = The Descending Order of items
        ** $limit  = Number of records that will be fetched from users
        */

        function getLatest($select, $table, $order, $limit = 5) {
            global $con;

            $getStatment = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

            $getStatment->execute();
            
            $rows = $getStatment->fetchAll();

            return $rows;
        }