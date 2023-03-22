<?php
// session_start();
// require 'connectDB.php';
// if(!empty($_SESSION["user_id"])){
//     $user_id = $_SESSION["user_id"];
//     $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
//     $row = mysqli_fetch_assoc($result);
// }else{
//     header("Location: login.php");
// }

session_start();
if(empty($_SESSION["user_id"])){
    header("Location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel = "stylesheet" href = "css/style.css">
    <script src="https://kit.fontawesome.com/41893cf31a.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeChat</title>
</head>
<body>
    <div class = "nav">
        <a href="viewAccount.php" class = "button"> <?php echo $_SESSION["username"]; ?></a> 
        <a href="createAccount.php" class = "button"> Login</a> 
        <input type = "text" placeholder = "Type here to search..">
        <a href= "#filter" class = "button"><i class="fa-solid fa-filter"></i></a>
        <a href= "home.php" class = "button"><i class="fa-solid fa-house"></i></a>
        <a href= "settings.php" class = "button"><i class="fa-solid fa-gear"></i></a>
        <a href = "logout.php" class = "button">Logout</a>
    </div>

<div class = "flex-container">
    <div class = "flex">
        <div class = "createPost">
            <a href="createPost.php" class = "button">Post</a>
        </div>
        <div class = "scroll">
        <?php 
        require_once 'connectDB.php';
        require_once "updateScore.php";
        $query = "SELECT p.*, u.username, c.community_name FROM posts p 
        INNER JOIN users u ON p.created_by = u.user_id
        INNER JOIN communities c ON p.community_id = c.community_id";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $community_name = $row['community_name'];
            $community_id = $row['community_id'];
            $title = $row['title'];
            $post_id = $row['post_id'];
            echo '<div class = "posts">';
            echo '<div class = "top">';
            echo '<p style = "color:#A67EF3; font-size: .8em;">'.$row['username'].'</p>';
            echo '<p style = "color:#A67EF3; font-size: .8em;"><a href = "community.php?community_id='.$community_id.'">'.$community_name.'</a></p>';
            echo '</div>';
            echo '<a href = "viewPost.php?post_id='.$post_id.'">'.$title.'</a>';
            echo '<div class="postContainer">';
            echo '<div class="postScore">' . $row['score'] . '</div>';
            echo '<div class="upvote" style="cursor: pointer;" data-postid="' . $row['post_id'] . '"><i class="fa-solid fa-arrow-up"></i></div>';
            echo '<div class="downvote" style="cursor: pointer;" data-postid="' . $row['post_id'] . '"><i class="fa-solid fa-arrow-down"></i></div>';
            echo '<a href="viewPost.php?post_id='.$post_id.'" class="commentButton" style="cursor: pointer;"><i class="fa-regular fa-comment"></i></a>';
            echo '</div>';
            echo '</div>';
        }
        ?>
        
        <script>
    function redirectToPost(post_id){
        window.location.href = "viewPost.php?post_id="+post_id;
    }

    // Get the upvote and downvote buttons
    const upvoteButtons = document.querySelectorAll('.upvote');
    const downvoteButtons = document.querySelectorAll('.downvote');

    // Add a click event listener to each upvote button
    upvoteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const postId = button.dataset.postid;
            const scoreElement = button.parentNode.querySelector('.postScore');

            // Make an AJAX call to update the post score
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateScore.php');
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the score in the UI
                    const newScore = JSON.parse(xhr.responseText).newScore;
                    scoreElement.innerHTML = newScore; ,
                    button.classList.add('active');
                    button.parentNode.querySelector('.downvote').classList.remove('active');
                } else {
                    console.error('Error updating score');
                }
            };
            xhr.send(`postId=${postId}&vote=up`);
        });
    });

    // Add a click event listener to each downvote button
    downvoteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const postId = button.dataset.postid;
            const scoreElement = button.parentNode.querySelector('.postScore');

            // Make an AJAX call to update the post score
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateScore.php');
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the score in the UI
                    const newScore = JSON.parse(xhr.responseText).newScore;
                    scoreElement.innerHTML = newScore;
                    button.classList.add('active');
                    button.parentNode.querySelector('.upvote').classList.remove('active');
                } else {
                    console.error('Error updating score');
                }
            };
            xhr.send(`postId=${postId}&vote=down`);
        });
    });
</script>
    </div>
    </div>
    <div class = "flex-left"> 
        <div class = "popular">
            <?php 
            require_once 'connectDB.php';
            $query = 'SELECT c.community_name, u.community_id, COUNT(u.user_id)as numJoined FROM communities c JOIN user_community u
             ON c.community_id = u.community_id GROUP BY c.community_id ORDER BY numJoined DESC
            LIMIT 5';
            $result = mysqli_query($conn, $query);
            echo '<p style = "color:#A67EF3; font-size: 1.3em;" >Top Communities</p>';
            while($row = mysqli_fetch_assoc($result)) {
            $community_id = $row['community_id'];
            $community_name = $row['community_name'];
            echo '<p><a href = "community.php?community_id='.$community_id.'">'.$community_name.'</a></p>';
            }
             ?>
        </div>
        <div class = "categories">
            <p style = "color:#A67EF3; font-size: 1.3em;" ><a href="communityList.php" >Communities</a></p>
            <?php 
            require_once 'connectDB.php';
            $user_id = $_SESSION['user_id'];
            $query = "SELECT c.community_name, u.community_id FROM communities c JOIN user_community u ON c.community_id = u.community_id WHERE u.user_id = '$user_id' LIMIT 7";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)) {
            $community_id = $row['community_id'];
            $community_name = $row['community_name'];
            echo '<p><a href = "community.php?community_id='.$community_id.'">'.$community_name.'</a></p>';
            }
             ?>
            <a href="createCommunity.php" class = "button">Create</a>
        </div>
      
    </div>
</div>
<script src = "scripts/script.js"></script>
</body>
<footer>
    <p class = "tos">Terms of Service</p>
    <p class = "tos">About</p>
    <p class = "tos">Contact Us</p>
    <p class = "tos">FAQ</p>
    <p class = "tos">Privacy and Policy</p>
</footer>
</html>