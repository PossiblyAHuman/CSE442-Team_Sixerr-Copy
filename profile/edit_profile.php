<?php
include '../functions.php';
session_start(); //does this provide me with session id and session username?

//Assumption: session id stored in cookie, session username stored in cookie
//get session id, session username
//query database make sure session id is associated to session username (this is neccessary to display info associated with username)
// will need a get request when querying.
// if the session id is associated to username then display homepage, otherwise don't
//id 
if (!isset($_SESSION["id"])) {
   echo "Edit Profile Page isn't displayed for any partiular user, please login first to access your specific homepage. </br> Please <button><a href='../login/login.html'>Log In</a></button> to continue.";
   die();
}
else
{
   $username = get_username_from_id($_SESSION["id"]);
   $curr_user_posts_data = get_posts_by_user_id($_SESSION["id"]);
   $your_requests = generate_request_htmlString($curr_user_posts_data);
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
         .gigs-text{color: white;}
         nav{background-color: rgb(67, 64, 70);}
         .settings-menu {background-color: rgb(85, 67, 67);}
         .profile-box {background-color: rgb(208, 205, 205);}
         .light-button{color: yellow;}
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

   include('edit_profile.html');
}


?>













