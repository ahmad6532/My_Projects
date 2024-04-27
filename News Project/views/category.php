<?php
include('header.php');
include('connection_config.php');
$query = "select * from category";
$res = mysqli_query($conn, $query) or dir("Query Fail");


?>



<div class="show-cat-outer">
    <div class="show-cat_div ">
        <div class="padd-cls"></div>
        <div class="add-cat-link"><a href="/views/add_category.php">Add New Category</a></div>
        <div class="padd-cls"></div>
        <?php if (mysqli_num_rows($res) > 0) { ?>
            <table class="category-show-table">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>No of Posts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($data = mysqli_fetch_assoc($res)) {
                    ?>

                        <tr>
                            <td><?php echo $data['c_id']; ?></td>
                            <td><?php echo $data['c_name']; ?></td>
                            <td><?php echo $data['no_post']; ?></td>
                        </tr>


                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="padd-cls"></div>
            <div class="padd-cls"></div>

        <?php
        } else {
            echo "<div class='add-post-wrong'>Something Went Wrong</div>";
        }
        ?>
    </div>

</div>

<?php

include('footer.php');
?>