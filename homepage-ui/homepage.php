<?php
include '../functions.php';
session_start(); 

if (!isset($_SESSION["id"])) {
   echo "Home page isn't displayed for any partiular user, please login first to access your specific homepage. </br> Please <button><a href='../login/login.html'>Log In</a></button> to continue.";
   die();  
}else{
   $all_post_data = get_posts();
	$curr_user_posts_data = get_posts_by_user_id($_SESSION["id"]);
   $your_requests = generate_request_htmlString($curr_user_posts_data);
   $all_requests = generate_request_htmlString($all_post_data);
   $id = $_SESSION["id"];

   $CSS_dark_mode;
   if(!isset($_COOKIE[$cookie_name]))
   {
      //Sets the cookie for 10 years
      setcookie($cookie_name, "false", time() + (86400 * 30 * 12 * 10), "/");
   }

   if ($_COOKIE[$cookie_name] == "true"){
      //display darkmode
      $CSS_dark_mode = "
      <style>
         body {background-color: rgb(98, 94, 100);}
         nav{background-color: rgb(67, 64, 70);}
         .settings-menu {background-color: rgb(85, 67, 67);}
         .profile-box {background-color: rgb(208, 205, 205);}
         .YourRequests {color: whitesmoke;}
         .AllRequests {color: whitesmoke;}
         .PlusButton {color:whitesmoke;}
         .OverFlowPosts {background-color: rgb(98, 94, 100);}
      </style>
      ";
   }
   else
   {
      //display lightmode
      $CSS_dark_mode = "";
   }
   include('homepage.html');
}
   
?>
