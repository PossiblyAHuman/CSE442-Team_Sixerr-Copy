<?php
    include '../../functions.php';
    
	$servername = "oceanus.cse.buffalo.edu:3306";
	$username = "azhou7";
	$password = "50375623";
	$db = "cse442_2023_spring_team_t_db";
            
	$conn = mysqli_connect($servername, $username, $password, $db);

    $query = $_GET['query'];
    $newest = $_GET['Newest'];
    $oldest = $_GET['Oldest'];
    $highest = $_GET['HighestRating'];
    $lowest = $_GET['LowestRating'];
    $id = $_GET['id'];

    $query = htmlspecialchars($query);
    $newest = htmlspecialchars($newest);
    $oldest = htmlspecialchars($oldest);
    $highest = htmlspecialchars($highest);
    $lowest = htmlspecialchars($lowest);
    $id = htmlspecialchars($id);

    http_response_code(200);
?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../homepage.css" />
        <link rel="stylesheet" type="text/css" href="searchFrontend.css"/>
        <script src="../homepage.js" defer></script>
    </head>
    <body>
    <nav>
        <a href="../homepage.php"> <img class="logo" src="../images/logoclear_001.png" alt="Sixerr Logo"></a>
        <center><h1>All Posts</h1></center>
        <a href=<?php echo '../../review/viewProfileAPI.php/?id=' . $id; ?>><img class="profile-pic" src=<?php echo get_curr_user_pfp(); ?> alt="profile"></a>
    </nav>
    <div class="searchFilter">
        <form action="searchFrontend.php" method="GET">
            <input type="text" name="query" class="Search-Bar"  placeholder="  Search...">
            <!--
            <input type = "hidden" name = "Newest" id = "Newest2" value = <?php echo $newest?>>
            <input type = "hidden" name = "Oldest" id = "Oldest2" value = <?php echo $oldest?>>
            <input type = "hidden" name = "HighestRating" id = "HighestRating2" value = <?php echo $highest?>>
            <input type = "hidden" name = "LowestRating" id = "LowestRating2" value = <?php echo $lowest?>>
            -->
        </form>
        <button onclick="openFilterMenu()">
            Filter ▶
        </button>
    </div>
        <div id="FilterMenu" class = "FilterMenu">
        <h1>Search Filter:</h1>
        <!-- TODO change to <form action="/search_function.php"> or something of the sort-->
        <form action="searchFrontend.php" method = "GET">
            <a href="javascript:void(0)" class="closebutton" onclick="closeFilterMenu()">&times;</a>
            <div>
                <input type="checkbox" id="Newest" name="Newest">
                <label for="Newest"> Newest</label><br>
            </div>
            <div>
                <input type="checkbox" id="Oldest" name="Oldest">
                <label for="Oldest"> Oldest</label><br>
            </div>
            <div>
                <input type="checkbox" id="HighestRating" name="HighestRating">
                <label for="HighestRating"> Highest Cost</label><br>
            </div>
            <div>
                <input type="checkbox" id="LowestRating" name="LowestRating">
                <label for="LowestRating"> Lowest Cost</label><br>
            </div>
	    <div>
		<input type = "hidden" name = "query" value = <?php echo $query?>>
	    </div>
            <input class = "SearchButton" type="submit" value="Search">

	    
          </form>
    </div>

    <div class = "ReqFilter">
        <div id="card-container">
        </div>
    </div>
    </body>
</html>

<script>

let numba = <?php 
if((strlen($newest) != 0) && (strlen($oldest) == 0) && (strlen($highest) == 0) && (strlen($lowest) == 0))
{
    $numba = 1;
    echo $numba;
}
else if((strlen($newest) == 0) && (strlen($oldest) != 0) && (strlen($highest) == 0) && (strlen($lowest) == 0))
{
    $numba = 2;
    echo $numba;
}
else if((strlen($newest) == 0) && (strlen($oldest) == 0) && (strlen($highest) != 0) && (strlen($lowest) == 0))
{
    $numba = 3;
    echo $numba;
}
else if((strlen($newest) == 0) && (strlen($oldest) == 0) && (strlen($highest) == 0) && (strlen($lowest) != 0))
{
    $numba = 4;
    echo $numba;
}
else if((strlen($newest) == 0) && (strlen($oldest) == 0) && (strlen($highest) == 0) && (strlen($lowest) == 0))
{
    $numba = 5;
    echo $numba;
}
else
{
    $numba = 0;
    echo $numba;
    http_response_code(400);
}
?>;

if(numba == 1)
{
    document.getElementById("Newest").checked = true;
}
else if(numba == 2)
{
    document.getElementById("Oldest").checked = true;
}
else if(numba == 3)
{
    document.getElementById("HighestRating").checked = true;
}
else if(numba == 4)
{
    document.getElementById("LowestRating").checked = true;
}
else
{}




var array;

if(numba == 1 || numba == 2 || numba == 5)
{
    var array = [
        <?php
            //$query = "ca";
	        if(strlen($query) == 0)
            {
                $stmt = $conn->prepare("SELECT * FROM posts");
                $stmt->execute();
                $raw_results = $stmt->get_result();

                if(mysqli_num_rows($raw_results) > 0)
                {
                    while($results = mysqli_fetch_array($raw_results)){
                        $title = $results["title"];
                        echo '"'.$title.'",';
                        $post = $results["post_id"];
                        echo '"'.$post.'",';
                        $image = $results["image"];
                        echo '"'.$image.'",';

                        $userid = $results["user_id"];
			            $usernomen = $conn->query("SELECT username FROM users WHERE id = $userid")->fetch_object()->username;  
                        echo '"'.$usernomen.'",';

			            $picture = $conn->query("SELECT picture FROM users WHERE id = $userid")->fetch_object()->picture;  
                        echo '"'.$picture.'",';
                    }
                }
                else
                {
		    $title = "No search result";
                    echo '"'.$title.'",';
                    $post = -1;
                    echo '"'.$post.'",';
                    $image = null;
                    echo '"'.$image.'",';
                    $usernomen = "error";
                    echo '"'.$usernomen.'",';
                    $picture = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/profile/images/blank_profile_pic.png";
                    echo '"'.$picture.'",';
                }
            }
            else if(strlen($query) > 2)
            {
                $stmt = $conn->prepare("SELECT * FROM posts WHERE (title LIKE ?) OR (description like ?)");

                $query = "%$query%";

                $stmt->bind_param('ss', $query, $query);
                $stmt->execute();
                $raw_results = $stmt->get_result();

                if(mysqli_num_rows($raw_results) > 0)
                {
                    while($results = mysqli_fetch_array($raw_results)){
                        $title = $results["title"];
                        echo '"'.$title.'",';
                        $post = $results["post_id"];
                        echo '"'.$post.'",';
                        $image = $results["image"];
                        echo '"'.$image.'",';

                        $userid = $results["user_id"];
			            $usernomen = $conn->query("SELECT username FROM users WHERE id = $userid")->fetch_object()->username;  
                        echo '"'.$usernomen.'",';

			            $picture = $conn->query("SELECT picture FROM users WHERE id = $userid")->fetch_object()->picture;  
                        echo '"'.$picture.'",';
                    }
                }
                else
                {
		            $title = "No search result";
                    echo '"'.$title.'",';
                    $post = -1;
                    echo '"'.$post.'",';
                    $image = null;
                    echo '"'.$image.'",';
                    $usernomen = "error";
                    echo '"'.$usernomen.'",';
                    $picture = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/profile/images/blank_profile_pic.png";
                    echo '"'.$picture.'",';
                }
            }
            else
            {
                $title = "Search phrase was too short";
                echo '"'.$title.'",';
                $post = -1;
                echo '"'.$post.'",';
                $image = null;
                echo '"'.$image.'",';
                $usernomen = "error";
                echo '"'.$usernomen.'",';
                $picture = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/profile/images/blank_profile_pic.png";
                echo '"'.$picture.'",';

            }
        ?>
    ];

    if(numba == 1)
    {
        array = array.reverse();
    }

}
else if(numba == 3 || numba == 4)
{
    var array = [
        <?php 
        if(strlen($query) == 0)
        {
            $stmt = $conn->prepare("SELECT * FROM posts ORDER BY price ASC");
            $stmt->execute();
            $raw_results = $stmt->get_result();

            if(mysqli_num_rows($raw_results) > 0)
            {
                while($results = mysqli_fetch_array($raw_results)){
                    $title = $results["title"];
                    echo '"'.$title.'",';
                    $post = $results["post_id"];
                    echo '"'.$post.'",';
                    $image = $results["image"];
                    echo '"'.$image.'",';

                    $userid = $results["user_id"];
		            $usernomen = $conn->query("SELECT username FROM users WHERE id = $userid")->fetch_object()->username;  
                    echo '"'.$usernomen.'",';

		            $picture = $conn->query("SELECT picture FROM users WHERE id = $userid")->fetch_object()->picture;  
                    echo '"'.$picture.'",';

                }
            }
            else
            {
                $title = "No search result";
                echo '"'.$title.'",';
                $post = -1;
                echo '"'.$post.'",';
                $image = null;
                echo '"'.$image.'",';
                $usernomen = "error";
                echo '"'.$usernomen.'",';
                $picture = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/profile/images/blank_profile_pic.png";
                echo '"'.$picture.'",';
            }
            
        }
        else if(strlen($query) > 2)
        {
            $stmt = $conn->prepare("SELECT * FROM posts WHERE (title LIKE ?) OR (description like ?) ORDER BY price ASC");

            $query = "%$query%";

            $stmt->bind_param('ss', $query, $query);
            $stmt->execute();
            $raw_results = $stmt->get_result();

            if(mysqli_num_rows($raw_results) > 0)
            {
                while($results = mysqli_fetch_array($raw_results)){
                    $title = $results["title"];
                    echo '"'.$title.'",';
                    $post = $results["post_id"];
                    echo '"'.$post.'",';
                    $image = $results["image"];
                    echo '"'.$image.'",';

                    $userid = $results["user_id"];
		            $usernomen = $conn->query("SELECT username FROM users WHERE id = $userid")->fetch_object()->username;  
                    echo '"'.$usernomen.'",';

		            $picture = $conn->query("SELECT picture FROM users WHERE id = $userid")->fetch_object()->picture;  
                    echo '"'.$picture.'",';
                }
            }
            else
            {
                $title = "No search result";
                echo '"'.$title.'",';
                $post = -1;
                echo '"'.$post.'",';
                $image = null;
                echo '"'.$image.'",';
                $usernomen = "error";
                echo '"'.$usernomen.'",';
                $picture = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/profile/images/blank_profile_pic.png";
                echo '"'.$picture.'",';
            }
        }
        else
        {
            $title = "Search phrase was too short";
            echo '"'.$title.'",';
            $post = -1;
            echo '"'.$post.'",';
            $image = null;
            echo '"'.$image.'",';
            $usernomen = "error";
            echo '"'.$usernomen.'",';
            $picture = "https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/profile/images/blank_profile_pic.png";
            echo '"'.$picture.'",';

        }    
        ?>

    ];

    if(numba == 3)
    {
        array = array.reverse();
    }
}
else
{
    var array = ["Error: Improper Sorting", -1, null, "error", null];
}

    



    const cardContainer = document.getElementById("card-container");
    
    //const cardLimit = 17; //cardLimit should be the total number of posts
    const cardLimit = array.length;
    const cardIncrease = 9;
    const pageCount = Math.ceil(cardLimit / cardIncrease);
    let currentPage = 1;
    
    var throttleTimer;
    const throttle = (callback, time) => {
        if (throttleTimer) return;
        
        throttleTimer = true;
        
        setTimeout(() => {
        callback();
        throttleTimer = false;
        }, time);
    };
    
    const getRandomColor = () => {
        const h = Math.floor(Math.random() * 360);
        
        //return `hsl(${h}deg, 90%, 85%)`;
	    return `hsl(0, 0%, 75%)`;
    };

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //we just need to modify this part
    const createCard = (index) => {
        if(numba == 1 || numba == 3)
        {
            const card = document.createElement("div");
            card.className = "card";
            card.innerHTML = "<div class = 'box'/> <div class = 'pfp'/> <img src ='" + array[5*(index-1)] + "' class = 'pfpclass'> </div/> <div class = 'text'/>Title: " + array[5*(index-1) + 4] + "<br/><br/>User:" + array[5*(index-1)+1] + "</div/></div/>";
            card.style.backgroundImage = "url(" + array[5*(index-1)+2] + ")";
            card.style.backgroundColor = getRandomColor();
            cardContainer.appendChild(card);
            card.onclick = function () {
                location.href = "../../offers/get-post.php/?postId=" + array[5*(index-1) + 3];

            };
        }
        else
        {
            const card = document.createElement("div");
            card.className = "card";

            card.innerHTML = "<div class = 'box'/><div class = 'pfp'/> <img src ='" + array[5*(index-1) + 4] + "' class = 'pfpclass'> </div/><div class = 'text'/>Title: " + array[5*(index-1)] + "<br/><br/>User: " + array[5*(index-1) + 3] + "</div/></div/>";
            
	    card.style.backgroundImage = "url(" + array[5*(index-1) + 2] + ")";
            card.style.backgroundColor = getRandomColor();
            cardContainer.appendChild(card);
            card.onclick = function () {
                location.href = "../../offers/get-post.php/?postId=" + array[5*(index-1) + 1];

            };
        }
    };
    
    const addCards = (pageIndex) => {
	//alert("hi" + array[0] + "bye");
        currentPage = pageIndex;
        
        const startRange = (pageIndex - 1) * cardIncrease;
        const endRange =
        currentPage == pageCount ? cardLimit : pageIndex * cardIncrease;
        
        //current the program is inputting a number into createCard and displays it.
        //we just need to input the data into createCard, and then modify createCard to display the correct info
        for (let i = startRange + 1; i <= endRange; i++) {
            if(i <= cardLimit/5)
            {
                createCard(i);
            }

        }
    };

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    const handleInfiniteScroll = () => {
        throttle(() => {
        const endOfPage =
        window.innerHeight + window.pageYOffset >= document.body.offsetHeight;
        
        if (endOfPage) {
        addCards(currentPage + 1);
        }
        
        if (currentPage >= pageCount) {
        removeInfiniteScroll();
        }
        }, 1000);
    };
    
    const removeInfiniteScroll = () => {
        window.removeEventListener("scroll", handleInfiniteScroll);
    };
    
    window.onload = function () {
        addCards(currentPage);
    };
    
    window.addEventListener("scroll", handleInfiniteScroll);
    </script>