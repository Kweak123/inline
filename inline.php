<?php
$post_string = file_get_contents("posts.json");
$comment_string = file_get_contents("comments.json");


// Создаем соединение
$conn = mysqli_connect('localhost', 'root', '', 'inline');

// Проверяем соединение
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "";


$posts = json_decode($post_string);
$comments = json_decode($comment_string);

foreach ($posts as $post) {
    $userId = $post->userId;
    $id = $post->id;
    $title = $post->title;
    $body = $post->body;
    mysqli_query($conn, "REPLACE INTO posts (userId, id, title, body) VALUES ($userId, $id, '$title', '$body')");
    echo mysqli_error($conn);
}

foreach ($comments as $comment) {
    $postId = $comment->postId;
    $id = $comment->id;
    $name = $comment->name;
    $email = $comment->email;
    $body = $comment->body;
    mysqli_query($conn, "REPLACE INTO comments (postId, id, name, email, body) VALUES ($postId, $id, '$name', '$email', '$body')");
    echo mysqli_error($conn);
}

if (isset($_POST['search'])) {
    $data = mysqli_query($conn, "SELECT * FROM comments WHERE body LIKE '%$_POST[comment]%'");
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>inline</title>
</head>
<body>
<div class="d-flex align-items-center flex-column">
    <form action="inline.php" method="post" class="w-50">
        <div class="input-group mt-5">
            <input type="text" class="form-control" placeholder="Поиск записи"
                   name="comment" aria-describedby="button-addon2" minlength="3">
            <input class="btn btn-outline-secondary" type="submit" id="button-addon2" value="Найти" name="search">
        </div>
    </form>
    <div class="w-50 mt-5">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Поста</th>
                <th scope="col">Заголовок</th>
                <th scope="col">Комментарий</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($data)) :
                while ($x = mysqli_fetch_assoc($data)) :
                    $postInfo =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM posts WHERE id = $x[postId]"));
                    ?>
                    <tr>
                        <th scope="row"></th>
                        <td><?= $postInfo['id'] ?></td>
                        <td><?= $postInfo['title'] ?></td>
                        <td><?= $x['body'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
