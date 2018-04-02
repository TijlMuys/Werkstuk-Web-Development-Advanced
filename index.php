<?php
    include_once "initialize.php";
    include_once "./database/BlogpostDB.php";
    include_once "./database/CommentDB.php";
    //$oldblogpost = BlogpostDB::getById(8);
    //$oldblogpost->ImageUrl = "https://i0.wp.com/www.tutorialmines.net/wp-content/uploads/2015/11/php-logo.png?fit=224%2C224";
    //BlogpostDB::delete($oldblogpost);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Document</title>
</head>
<body>
    <h1>This is a test for a MySQL database</h1>
    <p>Show data</p>
    <table border="1">
        <tr>
            <th>Id</th>
            <th>UserId</th>
            <th>CategoryId</th>
            <th>Title</th>
            <th>Date</th>
            <th>Content</th>
            <th>ImageUrl</th>
            <th>Number of comments</th>
        </tr>
        <?php
            //$blogpost = BlogpostDB::getById(3);
            $list = BlogpostDB::getAll();
            foreach ($list as $blogpost) {
        ?>
        <tr>
            <td><?php echo($blogpost->Id); ?></td>
            <td><?php echo($blogpost->UserId); ?></td>
            <td><?php echo($blogpost->CategoryId); ?></td>
            <td><?php echo($blogpost->Title); ?></td>
            <td><?php echo($blogpost->Date); ?></td>
            <td><?php echo($blogpost->Content); ?></td>
            <td><?php echo($blogpost->ImageUrl); ?></td>
            <td><?php echo(count(CommentDB::getByBlogpostId($blogpost->Id))); ?></td>
        </tr>
         <?php
            }
        ?>
    </table>
</body>
</html>