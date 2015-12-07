# Zdo
Zdo extends Pdo (inspired by Zend_Db)

Example:
--------

  <?php
  require 'Zdo.php';
  
  $db=new Zdo(array(
    'adapter'	=>'mysql',
    'host'		=>'localhost',
    'username'	=>'xxxxxxxx',
    'password'	=>'xxxxxxxx',
    'dbname'	=>'testdb',
  ));
  
  $q=$db->query('SELECT name, colour, calories FROM fruit WHERE calories<?', 150);
  while($r=$q->fetchAssoc()){
    echo $r['name'].' '.$r['colour'].' '.$r['calories']."\n";
  }
  ?>
