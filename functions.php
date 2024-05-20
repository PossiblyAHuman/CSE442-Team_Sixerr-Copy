<?php declare(strict_types=1);
define('IMAGE_DIR', 'image/'); # From functions.php (which is at the root)
define('ALLOWED_IMAGE_EXTENSIONS', ['png', 'jpg', 'jpeg']);
define('UPLOADS_FOLDER', '/web/CSE442-542/2023-Spring/cse-442t/uploads/');
define('UPLOADS_URL', 'https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/uploads/');

//Cookie for dark mode
$cookie_name = "is_dark_mode";

// Establish database connection
function connect_to_database() {
    $db_servername = "oceanus.cse.buffalo.edu:3306";
    $db_username = "jdmorris";
    $db_password = "50411768";
    $db_dbname = "cse442_2023_spring_team_t_db";

    $conn = new mysqli($db_servername, $db_username, $db_password, $db_dbname);

    if ($conn->connect_error) {
        return NULL;
    }

    return $conn;
}

function get_username_from_id(int $id) {
    session_start();
    if (!isset($_SESSION["id"])) {
        return NULL;
    }

    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    // Get username from user id
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if($result->num_rows !== 1) {
        return NULL;
    }

    $record = $result->fetch_assoc();
    $username = $record["username"];
    
    return $username;
}

// This only has public info, such as username and bio
function get_public_user_info_from_id(int $id) {
    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    $stmt = $conn->prepare("SELECT username, bio FROM users WHERE id = ?");
    $stmt->bind_param("i", htmlspecialchars($id));
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows !== 1) {
        return NULL;
    }

    $record = $result->fetch_assoc();

    return $record;
}

// Get association array of all info of currently logged in user
function get_user_info() {
    session_start();
    if (!isset($_SESSION["id"])) {
        return NULL;
    }

    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    // Get all user info from user id
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if($result->num_rows !== 1) {
        return NULL;
    }

    $record = $result->fetch_assoc();
    
    return $record;
}

# Gets the file extension from the image filename unless it's not valid, then it returns null
# Probably use with $_FILES['userfile']['name']
function get_image_file_extension(string $filename) {
    setlocale(LC_ALL,'en_US.UTF-8');
    $extension = strtolower(pathinfo(basename($filename), PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS)) {
        return null;
    }
    return $extension;
}

# Uploads an image from a HTML form, requires the $_FILES['userfile']['tmp_name'] and the extension
function upload_form_image(string $filename, string $extension) {
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS)) {
        return null;
    }

    if (!is_uploaded_file($filename)) {
        return null;
    }

    // Random 10 character alphanumeric string for filename
    $alphanumeric = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $image = '';
    do {
        for ($i = 0; $i < 10; $i++) {
            $image .= $alphanumeric[random_int(0, 61)];
        }
        $image .= '.' . $extension;
    } while (file_exists(UPLOADS_FOLDER . $image));

    if (!move_uploaded_file($filename, UPLOADS_FOLDER . $image)) {
        return null;
    }

    return UPLOADS_URL . $image;
}

# Insert post into database
# Returns false on failure and true on success
# Must be logged in
function insert_post(string $title, int $time, int $price, string $description, string $filename, string $extension) {
// function insert_post(string $title, int $time, int $price, string $description) {
    session_start();
    if (!isset($_SESSION['id'])) {
        return false;
    }

    if (strlen($title) < 1) {
        return false;
    }

    if (strlen($title) > 64) {
        return false;
    }

    if (!is_numeric($time) || 0 > $time || $time > 1000000) {
        return false;
    }
    
    if (!is_numeric($price) || 0 > $price || $price > 1000000) {
        return false;
    }

    if (strlen($description) > 2000) {
        return false;
    }

    // Connect to database
    $conn = connect_to_database();
    if ($conn === null) {
        return false;
    }

    // Write image
    $image = upload_form_image($filename, $extension);
    if ($image === null) {
        return false;
    }

    // SQL post insertion query
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, time, price, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiss", $_SESSION['id'], htmlspecialchars($title), $time, $price, htmlspecialchars($description), $image);
    $result = $stmt->execute();

    // // SQL post insertion query
    // $stmt = $conn->prepare("INSERT INTO posts (user_id, title, time, price, description) VALUES (?, ?, ?, ?, ?)");
    // $stmt->bind_param("isiis", $_SESSION['id'], htmlspecialchars($title), $time, $price, htmlspecialchars($description));
    // $result = $stmt->execute();

    $stmt->close();
    $conn->close();

    if (!$result) {
        return false;
    }

    return true;
}

# Retrieves association array of all post fields, null otherwise
function get_post_from_post_id(int $id) {
    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    $stmt = $conn->prepare("SELECT user_id, title, time, price, description, image FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if($result->num_rows !== 1) {
        return NULL;
    }

    $record = $result->fetch_assoc();
    
    return $record;
}

# Get ALL posts as an array of association arrays
function get_posts() {
    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    $stmt = $conn->prepare("SELECT post_id, user_id, title, time, price, description, image FROM posts");
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    $records = $result->fetch_all(MYSQLI_ASSOC);

    return $records;
}

# Get all posts created by user_id as an array of association arrays
function get_posts_by_user_id(int $user_id) {
    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    $stmt = $conn->prepare("SELECT post_id, user_id, title, time, price, description, image FROM posts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    $records = $result->fetch_all(MYSQLI_ASSOC);

    return $records;
}

function get_username_from_userid(int $userid) {
    session_start();
    if (!isset($_SESSION["id"])) {
        return NULL;
    }

    $conn = connect_to_database();
    if ($conn === NULL) {
        return NULL;
    }

    // Get username from user id
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if($result->num_rows !== 1) {
        return NULL;
    }

    $record = $result->fetch_assoc();
    $username = $record["username"];
    
    return $username;
}

# Return true if admin or false if not
function is_admin(int $user_id) {
    return $user_id === 0; # The only admin is the one of user_id 0
}

# Delete post only if admin
function remove_post(int $post_id) {
    session_start();
    if (!isset($_SESSION["id"])) {
        return false;
    }

    $conn = connect_to_database();
    if ($conn === NULL) {
        return false;
    }

    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();

    if($result->num_rows !== 1) {
        return false;
    }

    $record = $result->fetch_assoc();
    $user_id = $record["user_id"];

    if (!is_admin($_SESSION["id"]) && $user_id !== $_SESSION["id"]) {
        return false;
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    return true;
}


   /*Generates a PHP string containing all of the HTML code needed by either the Your Requests section or All Requests section*/ 
   function generate_request_htmlString($post_data){
    $req_str = "";
    foreach($post_data as $user_post){
       $user_id = $user_post['user_id'];
       $username = "random";
       $username = get_username_from_userid($user_id);
       $post_id = $user_post['post_id'];
       $title = $user_post['title'];
       $time = $user_post['time'];
       $price = $user_post['price'];
       $description = $user_post['description'];
       $image = $user_post['image'];

       $conn = connect_to_database();
	   $picture = $conn->query("SELECT picture FROM users WHERE id = $user_id")->fetch_object()->picture;  
       $html_string = generate_post_html($post_id, $username, $title, $description, $price, $image, $picture);
       $req_str.= $html_string;
    }
    return $req_str;
 }

 /*Generates the html for a post */
 function generate_post_html($post_id, $username, $title, $description, $price, $image, $picture){
    return "
            <div class=\"PostBox\" onclick = \"redirect($post_id);\">
            <div class = \"Container1\">
            <div class =\"title\">$title</div>
            <img src= $image alt=\"Post Image\">
            <div class = \"DescContainer\">
                <img src= $picture alt=\"Profile Image\">
                <div class = \"wombat\">
                        <div class = \"username\"> $username</div>
                        <div class = \"description\"> $description </div>
                </div>
            </div>
            </div>
            <div class = \"Container2\">

        <!--
            <div class = \"RatingContainer\">
                <span class=\"fa fa-star ratingStar\"></span>
                4.7
            </div>
        -->
            <div class = \"Price\"> Starting at $$price  </div>
            </div>
            </div>

            <script>
            function redirect(postid) {

                window.location.href = \"../offers/get-post.php/?postId=\" + postid;
            }
            </script>
            ";

 }

# Returns default pfp is something goes wrong
function get_curr_user_pfp() {
    $default_pfp = 'https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/homepage-ui/images/blank_profile_pic.png';
    session_start();
    if (!isset($_SESSION["id"])) {
        return $default_pfp;
    }

    $conn = connect_to_database();
    if ($conn === NULL) {
        return $default_pfp;
    }

    // Get username from user id
    $stmt = $conn->prepare("SELECT picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if($result->num_rows !== 1) {
        return $default_pfp;
    }

    $record = $result->fetch_assoc();
    $picture = $record["picture"];
    
    return $picture;
}

# Returns default pfp is something goes wrong
function get_pfp_by_id(int $id) {
    $default_pfp = 'https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/homepage-ui/images/blank_profile_pic.png';

    $conn = connect_to_database();
    if ($conn === NULL) {
        return $default_pfp;
    }

    // Get username from user id
    $stmt = $conn->prepare("SELECT picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if($result->num_rows !== 1) {
        return $default_pfp;
    }

    $record = $result->fetch_assoc();
    $picture = $record["picture"];
    
    return $picture;
}
?>
