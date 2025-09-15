<!DOCTYPE html>
<html lang="en">

<head>
    <title>Redirect</title>
</head>

<body>
    <script>
        window.top.location = '<?= $redirect ?>';
    </script>
    <p>If you are not redirected, <a href="<?= $redirect ?>" target="_top">click here.</a></p>
</body>

</html>