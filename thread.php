<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        #ques{
            min-height: 433px;
        }
    </style>
    <title>Welcome to iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>
    <?php
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` WHERE thread_id=$id"; 
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_user_id = $row['thread_user_id'];

        // Query the users table to find out the name of OP
        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $posted_by = $row2['user_email'];
   }
    
    ?>
    <?php
    $showAlert=false;
    $method=$_SERVER['REQUEST_METHOD'];
    if($method=='POST'){
        // Insert into comment db
        $comment = $_POST['comment'];
        $comment = str_replace("<", "&lt;", $comment);
        $comment = str_replace(">", "&gt;", $comment); 
        $sno=$_POST["sno"];
        $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno',current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your comment has added has been added!              
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                  </div>';
        }  
    }
    ?>


    <!-- Category container starts here -->
    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $title;?></h1>
            <p class="lead">  <?php echo $desc;?></p>
            <hr class="my-4">
            <p>This is a peer to peer forum. No Spam / Advertising / Self-promote in the forums is not allowed. Do not post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post questions. Remain respectful of other members at all times.</p>
            <p>Posted by: <em><?php echo $posted_by; ?></em></p>
        </div>
    </div>
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        echo'
    <div class="container">
    <h1 class="py-2">Post a Comment</h1>
    <form action="'. $_SERVER["REQUEST_URI"] . '" method="post">
    

  <div>
    <label for="exampleFormControlTextarea1">Type of comment</label>
    <textarea class="form-control"id="comment" rows="3" name="comment"></textarea>
    <input type="hidden" name="sno" value="'.$_SESSION["sno"].'"></div>
    <button type="submit" class="btn btn-success btn-lg my-2">Post Comment</button>
</form>
   </div>';
    }
    else{
            echo'
            <div class="container">
            <h1 class="py-2">Start a Comments</h1> 
               <p class="lead">You are not logged in. Please login to be able to start a comment</p>
            </div>
            ';
           }
   ?>
    <div class="container mb-5" id="ques">
        <h1 class="py-2">comments</h1>
       
        <?php
    $id = $_GET['threadid'];
    $perPage = 3; // Number of comments per page
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $startFrom = ($page - 1);
    $startF = $startFrom * $perPage;

    // SQL query with pagination
    $sql = "SELECT COUNT(*) AS total_rows FROM `comments` WHERE thread_id=$id"; 
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $totalPages = ceil($row["total_rows"] / $perPage);

    // Fetch comments for the current page
    $sql_comments = "SELECT * FROM `comments` WHERE thread_id=$id LIMIT $startF, $perPage"; 
    $result_comments = mysqli_query($conn, $sql_comments);
    $noresult = true;
    while($row_comment = mysqli_fetch_assoc($result_comments)){
        $noresult = false;
        $id = $row_comment['comment_id'];
        $content = $row_comment['comment_content']; 
        $comment_time = $row_comment['comment_time']; 
        $thread_user_id = $row_comment['comment_by']; 

        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);

        echo '<div class="media my-3">
            <img src="img/userdefault.png" width="54px" class="mr-3" alt="...">
            <div class="media-body">
            <p class="font-weight-bold my-0">'.$row2['user_email'].' at '.$comment_time.'</p>'.$content.'
            </div>
        </div>';

    }
        if($noresult){
            echo'<div class="jumbotron jumbotron-fluid">
            <div class="container">
              <p class="display-4">No Threads Found</p>
              <p class="lead">Be The First Person to ask a Question</p>
            </div>
          </div>';
        }
        
    // Pagination controls
//     echo '<nav aria-label="Page navigation example">
//     <ul class="pagination justify-content-center">';
// for ($i = 1; $i <= $totalPages; $i++) {
// echo '<li class="page-item"><a class="page-link" href="thread.php?threadid='.$id.'&page='.$i.'">'.$i.'</a></li>';
// }
// echo '</ul></nav>';
echo '<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item"><a class="page-link" href="thread.php?threadid='.$_GET['threadid'].'&page='.$i.'">'.$i.'</a></li>';
    }
    echo '</ul></nav>';
 
    ?> 
    
    </div>


    <?php include 'partials/_footer.php';?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>