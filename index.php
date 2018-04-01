<?php
    include_once "initialize.php";
    include_once "./database/BlogpostDB.php";
    $newblogpost = new Blogpost(-1, 1, 3, "Testtitel", -1, "Dit is een dummybericht", "https://imagejournal.org/wp-content/uploads/bb-plugin/cache/23466317216_b99485ba14_o-panorama.jpg");
    BlogpostDB::insert($newblogpost);
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
        </tr>
        <?php
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
        </tr>
         <?php
            }
        ?>
    </table>
</body>
</html>