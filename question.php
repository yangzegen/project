<?php
session_start();
// print_r($_SESSION);

include("includes/database.php");

$method = $_SERVER["REQUEST_METHOD"];
if($method == "POST"){
  $title = $_POST["title"];
  $reward = $_POST["reward"];
  $content= $_POST["content"];
  $file= $_FILES["file"]["tmp_name"];
  $accountID = $_SESSION["id"];
//create query string
    $question_query = "INSERT INTO question 
    (title, reward, content,accountID)
    VALUES
    (?,?,?,?)";
    
    $statement = $connection -> prepare($question_query);
    $statement -> bind_param("sdsi",$title,$reward,$content,$accountID);
    if($statement -> execute()){
      //question success
      //get questionID
      $questionID = $connection -> insert_id;
      //check if there is an image
      
      if($_FILES["file"]["tmp_name"]){
        $target_dir = "uploads/";
        $file_name = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check !== false) {
          //file is an image
          move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
           $image_query = "INSERT INTO image
          (file,accountID,questionID)
          VALUES (?,?,?)";
          //run the query
          $imgstatement = $connection -> prepare($image_query);
          $imgstatement -> bind_param("sii",$file_name,$accountID,$questionID);
          if($imgstatement -> execute()){
            echo "image success";
          }
        }
      }
    }
    
   
    
    //run query against database connection
    // $result = $connection->query($register_query);
    
    
    


}
?>
<!doctype html>
<html>
    <?php
    $page_title = "Home Page";
    include("includes/head.php");
    ?>
    <body>
      <div class="form-group text-">
      <?php include("includes/navigation.php"); ?>
      </div>
          <div class="container">
          <form id="register-form" action="<?php echo $currentpage;  ?>" method="post" enctype='multipart/form-data'>
            
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" name="title" class="form-control" id="title" placeholder="title">
            </div>
            <div class="form-group">
              <label for="reward">Reward</label>
              <input type="text" name="reward" class="form-control" id="reward" placeholder="000.00">
            </div>
            <div class="form-group">
              <label for="content">Content</label>
              <textarea class="form-control" name="content" rows="3"></textarea>
            </div>
            <div class="form-group">
             <label for="exampleInputFile">File input</label>
             <input type="file" name="file" id="exampleInputFile">
            </div>
            <div class="form-buttons text-center">
              <button type="submit" name="submit" value="Upload Image" class="btn btn-info">submit</button>
            </div>
          </form>
    </div>
    </body>
</html>