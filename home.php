<!--<?php
    
    session_start();
    require_once 'connectDB.php';
    $user = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

?>
-->
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
        <a href="viewAccount.php" class = "button"> <i class="fa-solid fa-user"></i></a> 
        <a href="createAccount.php" class = "button"> Login</a> 
        <input type = "text" placeholder = "Type here to search..">
        <a href= "#filter" class = "button"><i class="fa-solid fa-filter"></i></a>
        <a href= "home.php" class = "button"><i class="fa-solid fa-house"></i></a>
        <a href= "settings.php" class = "button"><i class="fa-solid fa-gear"></i></a>
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
            echo '<div class = "posts">';
            echo '<div class = "top">';
            echo '<p style = "color:#A67EF3; font-size: .8em;">'.$row['username'].'</p>';
            echo '<p style = "color:#A67EF3; font-size: .8em;">'.$row['community_name'].'</p>';
            echo '</div>';
            echo '<p onclick="redirectToPost();" style="cursor: pointer;">' . $row['title'] . '</p>';
            echo '<div class="postContainer">';
            echo '<div class="postScore">' . $row['score'] . '</div>';
            echo '<div class="upvote" style="cursor: pointer;" data-postid="' . $row['post_id'] . '"><i class="fa-solid fa-arrow-up"></i></div>';
            echo '<div class="downvote" style="cursor: pointer;" data-postid="' . $row['post_id'] . '"><i class="fa-solid fa-arrow-down"></i></div>';
            echo '<div class="commentButton" style="cursor: pointer;" onclick="redirectToPost();"><i class="fa-regular fa-comment"></i></div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
        
        <script>
    function redirectToPost(){
        window.location.href = "viewPost.php";
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
                    scoreElement.innerHTML = newScore;
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
            <p style = "color:#A67EF3; font-size: 1.3em;" >Popular</p>
            <p>Manchester United</p>
            <p>Call of Duty</p>
            <p>Cats</p>
            <p>Turkey</p>
        </div>
        <div class = "categories">
            <p style = "color:#A67EF3; font-size: 1.3em;" ><a href="community.php" >Communities</a></p>
            <p>Gaming</p>
            <p>Sports</p>
            <p>Nature</p>
            <p>Business</p>
            <p>More...</p>
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