<?php 
require APPROOT . '/views/inc/header.php';
?>
<h1><?php echo $data['title'];  ?></h1>
<p><?php echo $data['description'];  ?></p>
<strong><p><?php echo 'Version ' .  APPVERSION;  ?></p></strong>
<?php 
require APPROOT . '/views/inc/footer.php';
?>