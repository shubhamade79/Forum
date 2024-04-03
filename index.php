<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
    
    <!-- Slider starts here -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://source.unsplash.com/1600x400/?coding,cpp water" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://source.unsplash.com/1600x400/?coding,python" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://source.unsplash.com/1600x400/?coding,java" class="d-block w-100" alt="...">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    
    <!-- Category container starts here -->
    <div class="container my-4" id="ques">
        <h2 class="text-center my-4">iDiscuss - Browse Categories</h2>
        <div class="row my-4">
          <!-- Fetch all the categories and use a loop to iterate through categories -->
         <?php 
         $perPage = 3; // Number of comments per page
         if (isset($_GET['page'])) {
             $page = $_GET['page'];
         } else {
             $page = 1;
         }
         $startFrom = ($page - 1);
         $startF = $startFrom * $perPage;
         // SQL query with pagination
    $sql = "SELECT COUNT(*) AS total_rows FROM `categories`"; 
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $totalPages = ceil($row["total_rows"] / $perPage);
        // Fetch comments for the current page

         $sql = "SELECT * FROM `categories` LIMIT $startF, $perPage"; 
         $result = mysqli_query($conn, $sql);
         while($row = mysqli_fetch_assoc($result)){
          // echo $row['category_id'];
          // echo $row['category_name'];
          $id = $row['category_id'];
          $cat = $row['category_name'];
          $desc = $row['category_description'];
          echo '<div class="col-md-4 my-2">
                  <div class="card" style="width: 18rem;">
                      <img src="https://source.unsplash.com/500x400/?'.$cat. ',coding" class="card-img-top" alt="image for this category">
                      <div class="card-body">
                          <h5 class="card-title"><a href="threadlist.php?catid=' . $id . '">' . $cat . '</a></h5>
                          <p class="card-text">' . substr($desc, 0, 80). '... </p>
                          <a href="threadlist.php?catid=' . $id . '" class="btn btn-primary">View Threads</a>
                      </div>
                  </div>
                  
                </div>';
         } 
         
         ?>
          </div>
          <?php
              echo'<div class="container text-center">Showing Categories <=3 Each Page</div>';
          echo '<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item"><a class="page-link" href="index.php?&page='.$i.'">'.$i.'</a></li>';
    }
    echo '</ul></nav>'; ?>
    </div>
    <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        $showAlert=false;
        if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Insert into categories db
        $user = $_POST['cat_name1'];
        $problem = $_POST['Describtion'];
        $sql2= "INSERT INTO `categories` (`category_name`, `category_description`, `created`) VALUES ('$user', '$problem', current_timestamp())";
        $result2= mysqli_query($conn, $sql2);
        $showAlert=true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your Categories has been added!              
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                  </div>';
    }
} 
echo'<div class="container my-3">
    <h1 class="text-center">Add Categories</h1>
    <form action="index.php" method="post">
  <div class="form-group">
    <label for="exampleFormControlInput1">Category Name</label>
    <input type="text" class="form-control" id="cat_name1" name="cat_name1">
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Description </label>
    <textarea class="form-control" id="Describtion" rows="3" name="Describtion"></textarea>
  </div>
  <button class="btn btn-success">Submit</button>
</form>
</div>';}

?>    
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