<?php
include '../functions.php';

# Validate proper request: Not POST, since we're not submitting a form. So should be GET.
if($_SERVER["REQUEST_METHOD"] == "POST") {
    http_response_code(405);
    echo "Server side error: Invalid Request!";
    die();
}

http_response_code(200);

# Create server connection
$conn = connect_to_database();

$id = $_GET["id"];

// Gets Username, Email, & Bio
$stmt = "SELECT username, email, bio FROM users WHERE id = '$id'";
$result = $conn->query($stmt);
$info = $result->fetch_assoc();
$user = $info["username"];
$email = $info["email"];
$bio = $info["bio"];

// Gets Reviews
$reviewsInHTML = "";

$stmt2 = "SELECT reviewer_id, review, rating, time FROM review where reviewee_id = '$id'";
$result2 = $conn->query($stmt2);

if ($result2->num_rows > 0) {
    $numReviews = $result2->num_rows;
    // Output data of each row
    while($numReviews > 0) {

        $info2 = $result2->fetch_assoc();
        $reviewee = $info2["reviewer_id"];
        $review = $info2["review"];
        $rating = $info2["rating"];
        $time = $info2["time"];

        // Gets reviewer username
        $stmt3 = "SELECT username FROM users where id = '$reviewee'";
        $result3 = $conn->query($stmt3);
        $info3 = $result3->fetch_assoc();
        $reviewerUser = $info3["username"];

        $reviewsInHTML = $reviewsInHTML."<div class='review'>";
        $reviewsInHTML = $reviewsInHTML."<p>".$reviewerUser."</p>";
        $reviewsInHTML = $reviewsInHTML."<p class='reviewText'>".$review."</p>";
        $reviewsInHTML = $reviewsInHTML."<p>".$rating."</p>";
        $reviewsInHTML = $reviewsInHTML."<p>".$time."</p>";
        $reviewsInHTML = $reviewsInHTML."</div>";
        $numReviews = $numReviews - 1;
    }
} else {
//   echo "0 results";
}

mysqli_close($conn);

session_start();
if (isset($_SESSION['id'])) {
    $pfp_link = '../viewProfileAPI.php/?id=' . $_SESSION['id'];
} else {
    $pfp_link = '';
}

if ($_SESSION['id'] == $id) {
    $edit_profile_button = "<a href=\"../../profile/edit_profile.php\"><input type=\"button\" value=\"EDIT PROFILE\"></a>";
} else {
    $edit_profile_button = '';
}
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Profile Sixxer</title> 
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <!-- <link rel = "icon" href = "../logoclear_001.png"/> -->
        <link rel="stylesheet" href="../viewProfileAPI.css"/>
    </head>

    <body>
        <nav>
            <a href="../../homepage-ui/homepage.php"><img class="logo" src="../images/logoclear_001.png" alt="Sixerr Logo"></a>
            <a href=<?php echo $pfp_link ?>><img class="profile-pic" src=<?php echo get_curr_user_pfp(); ?> alt="profile"></a>
        </nav>
        
        <?php echo $edit_profile_button; ?>

        <div class="Row">
            <div class="Bio">
                <h1><?php echo $user; ?> </h1>
                <img class="profile-pic-2" src=<?php echo get_pfp_by_id($id); ?> alt="profile">
                <p class = "wrapOver">Email: <?php echo $email; ?> </p>

                <p class = "wrapOver2">Biography: <?php echo $bio; ?>  </p>
            </div>

        </div>

        <div class="Row">
            <div class="Reviews">
                <h3>Reviews<h3>
                <a href="../writeReview.php/?id=<?php echo $id;?>"><button>Write a Review</button></a>
                <div class='review names'>
                    <p>Reviewer</p>
                    <p class="reviewText">Review Text</p>
                    <p>Rating</p>
                    <p>Review Date</p>
                </div>
                <?php echo $reviewsInHTML; ?>
            </div>
        </div>

    </body>
</html>