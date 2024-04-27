<?php

include('header.php');
include('connection_config.php');
$query = "select a.title,a.author,a.description,a.image,a.posted_date,a.p_id,b.c_name from post a, category b where a.category=b.c_id order by a.p_id";
$res = mysqli_query($conn, $query) or dir("Query Fail");


?>



<div class="show-cat-outer">
    <div class="show-cat_div ">
        <div class="padd-cls"></div>
        <div class="add-cat-link"><a href="/views/add_post.php">Add New Post</a></div>
        <div class="padd-cls"></div>
        <?php if (mysqli_num_rows($res) > 0) { ?>
            <table class="post-show-table">
                <thead>
                    <tr>
                        <th>Post ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Posted Date</th>
                        <th>Image</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($data = mysqli_fetch_assoc($res)) {
                    ?>

                        <tr>
                            <td><?php echo $data['p_id']; ?></td>
                            <td><?php echo $data['title']; ?></td>
                            <td><?php echo $data['author']; ?></td>
                            <td><?php $value = $data['description'];
                                echo substr($value, 0, 10); ?></td>
                            <td><?php echo $data['c_name']; ?></td>
                            <td><?php echo $data['posted_date']; ?></td>
                            <td><img src="<?php echo $data['image']; ?>"></td>
                            <td><a href="add_post.php?id=<?php echo $data['p_id'] ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="post_edit.php?id=<?php echo $data['p_id'] ?>"><i class="fa-solid fa-trash-can"></i></a></td>

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