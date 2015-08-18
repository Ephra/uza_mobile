<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
  //exec("mysqldump --user=root --password=''  --host=localhost unnett >{$_SERVER['DOCUMENT_ROOT']}/project/file.sql");
  exec("mysqldump -u root -p unnett>backup.sql");

?>
