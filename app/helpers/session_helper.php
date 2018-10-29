<?php
session_start();

/*
 * flash message Helper
 * EXPAMPLE -  flash('register_success', 'You are now registerd', --OPTIONAL 'alert alert-danger')
 * DISPLAY IN VIEW - <?php echo flash('register_success'); ?>
 */
function flash($flash = '', $message = '', $class = 'alert alert-success')
{
    if (!empty($flash) && !empty($message) && empty($_SESSION[$flash])) {
        $_SESSION[$flash] = $message;
        $_SESSION[$flash . '_class'] = $class;
    } elseif (empty($message) && !empty($_SESSION[$flash])) {
        $class = !empty($_SESSION[$flash . '_class']) ? $_SESSION[$flash . '_class'] : '';
        echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$flash] . '</div>';
        unset($_SESSION[$flash]);
        unset($_SESSION[$flash . '_class']);
    }
}


 function isLoggedIn()
 {
     if (isset($_SESSION['user_id'])) {
         return true;
     } else {
         return false;
     }
 }
