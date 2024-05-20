<?php
include '../functions.php';

# Validate proper request: Not POST, since we're not submitting a form. So should be GET. B/c the request will all be within the URL.
if($_SERVER["REQUEST_METHOD"] == "POST") {
    http_response_code(405);
    echo "Server side error: Invalid Request!";
    die();
}

http_response_code(200);

# Validate user 
session_start();
if(!isset($_SESSION["id"])) {
    echo "Please login to view a post and submit an offer.";
    http_response_code(400);
    die();
} //This will be helpful when adding the submit offer button/form. Will need to know the person submitting the offer.


/*# Server info
$servername = "oceanus.cse.buffalo.edu:3306";
$username = "tli58";
$password = "50351999";
$db = "cse442_2023_spring_team_t_db";

# Create server connection
$conn = mysqli_connect($servername, $username, $password, $db);

# Check server connection
if(!$conn) {
    http_response_code(400);
    die("Connection failed: " . mysqli_connect_error());
}*/


$id = $_GET["postId"]; //$_SESSION["id"];

// Gets the post object.
$result = get_post_from_post_id($id);
if ($result === NULL) {
    http_response_code(404);
    echo "Post does not exist";
    die();
}
$info = $result; //->fetch_assoc();
$user = $info["user_id"];
$title = $info["title"];
$time = $info["time"];
$price = $info["price"];
$description = $info["description"];
$image = $info["image"];

mysqli_close($conn);

if (is_admin($_SESSION['id']) || $_SESSION['id'] === $user) {
    $remove_post_button = "<input type=\"button\" value=\"REMOVE POST\" onclick=\"remove_post($id);\">";
} else {
    $remove_post_button = '';
}

if (isset($_SESSION['id'])) {
    $pfp_link = '../../review/viewProfileAPI.php/?id=' . $_SESSION['id'];
} else {
    $pfp_link = '';
}

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
         body {background-color: rgb(98, 94, 100); color: whitesmoke}
         nav{background-color: rgb(67, 64, 70);}
         .Row Div{border-color: gray;}
      </style>
      ";
   }
   else
   {
      //display lightmode
      $CSS_dark_mode = "";
   }
?>

<html>
    <head>
        <title>Profile Sixxer</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <script type="text/javascript" src="../sendOffer.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <!-- <link rel = "icon" href = "../logoclear_001.png"/> -->
        <link rel="stylesheet" href="../offers.css"/>
    </head>
    <?php echo $CSS_dark_mode;?>
    <body>
        

        <nav>
            <a href="../../homepage-ui/homepage.php"><img class="logo" src="../images/logoclear_001.png" alt="Sixerr Logo"></a>
            <a href=<?php echo $pfp_link ?>><img class="profile-pic" src=<?php echo get_curr_user_pfp(); ?> alt="profile"></a>
        </nav>

        <?php echo $remove_post_button; ?>

        <div>
            <h1><?php echo $title; ?></h1>
        </div>

        <div class="Row">
            <div class="">
                <img src= "<?php echo $image; ?>" >
            </div>
            <div class="">
                <h3>Post Owner:</h3>
                <h4>Post Owner:</h4>
                <p>The id of the person that posted this gig is <?php echo $user; ?></p>
                <a href="../../review/viewProfileAPI.php/?id=<?php echo $user; ?>">View the poster main public profile</a>
            </div>
        </div>



        <div class="Row">
            <div class="description">
                <h3>The description is:</h3>
                <h4>The description is:</h4>
                <?php echo $description; ?>
            </div>
        </div>


        <div class="Row">
            <div>
                <h3>The estimated time is:</h3>
                <h4>The estimated time is:</h4>
                <?php echo $time; ?>
            </div>
            <div>
                <h3>The pay will be:</h3>
                <h4>The pay will be:</h4>
                <?php echo $price; ?>
            </div>
        </div>


        <div class="Row">
            <div class="description">
                <h3>Wanna send an offer?</h3>
                <h4>Wanna send an offer?</h4>
                <form id = "center">
                    <input id="offerMessage" type="text" name="offerMessage" class="displayBlock" placeholder = "Enter your offer message.">
                    <input type="button" value="Send Offer" onclick="sendOffer(<?php echo $id; ?>, <?php echo $user; ?>);" class="loginButton">
                </form>
            </div>
        </div> 

        <div class="Row">
            <div id="offerResponse" class="offerResponseCSS">
            </div>
        </div> 

    </body>
</html>