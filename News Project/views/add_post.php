<?php
include('header.php');
include('connection_config.php');
$query = "select * from category";
$res = mysqli_query($conn, $query) or dir("Query Fail");

if (isset($_REQUEST['add_post'])) {
    $title = $_REQUEST['title'];
    $author = $_REQUEST['author'];
    $description = $_REQUEST['description'];
    $category = $_REQUEST['category'];
    $file_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $folder_path = "../posted_images/" . $file_name;
    if (move_uploaded_file($tmp_name, $folder_path)) {
        $insertion = "insert into post (title, description, image, category, author) values ('$title','$description','$folder_path','$category','$author')";
        $gain = mysqli_query($conn, $insertion) or dir('Not Run');
        if ($gain) {
            header("Location: {$_server_name}post.php");
        } else {
            echo "Data Not Uploaded";
        }
    } else {
        echo "File Not Uploaded";
    }
}
?>



<div class="add-post-outer">
    <div class="login_div">
        <div class="inner_div">
            <?php if (mysqli_num_rows($res) > 0) { ?>
                <h2>New Post</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">

                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter Title">
                    <span id="titlespan">Fill Title</span>
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" placeholder="Enter Author">
                    <span id="authorspan">Fill Author</span>
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                    <span id="descriptionspan">Fill Description</span>
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="Select Category">Select Category</option>

                        <?php
                        while ($data = mysqli_fetch_assoc($res)) {
                        ?>
                            <option value="<?php echo $data['c_id']; ?>"><?php echo $data['c_name']; ?></option>
                        <?php } ?>
                    </select>
                    <span id="categoryspan">Select Category</span>
                    <label for="image">Select Image</label>
                    <input type="file" id="image" name="image">
                    <span id="imagespan">Select Image</span>

                    <input type="submit" id="add_post" name="add_post" value="Submit">

                </form>
            <?php
            } else {
                echo "<div class='add-post-wrong'>Something Went Wrong</div>";
            }
            ?>
        </div>

    </div>

</div>

<?php

include('footer.php');
?>