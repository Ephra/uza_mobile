<?php

/**
 * Description of optimize
 *
 * @author user
 */
class optimize {
    /*  OPTIMIZE ALL TABLES  */
    
    function optimize_database($DATABASE_LINK) {
        $result = mysql_query('SHOW TABLES', $DATABASE_LINK) or die('Cannot get tables');
        while ($table = mysql_fetch_row($result)) {
            mysql_query('OPTIMIZE TABLE ' . $table[0], $DATABASE_LINK) or die('Cannot optimize ' . $table[0]);
        }
    }

}

?>
