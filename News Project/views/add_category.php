<?php
include('header.php');
include('connection_config.php');

if(isset($_POST['add_category']))
{
    $category=$_REQUEST['category'];
    $no_post=0;
    $query="insert into category (c_name,no_post) values('$category','$no_post')";
    $result=mysqli_query($conn,$query) or dir('Query Fail');
    if($result)
    {
        header("Location: {$server_name}category.php");
    }
    else{
        header("Location: {$server_name}post.php");

    }

}
?>



<div class="add-post-outer">
    <div class="login_div">

        <div class="inner_div">
            <h2>New Category</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                <label for="category">Category</label>
                <input type="text" id="new_category" name="category" placeholder="Enter Category">
                <span id="new_cat_span">Fill Category</span>
                <input type="submit" id="add_category" name="add_category" value="Submit">

            </form>
        </div>
    </div>

</div>

<?php
include('footer.php');
?>