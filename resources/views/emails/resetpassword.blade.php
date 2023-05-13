<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>
<body>
    <p>Dear , {{ $user->fname.' '.$user->lname }}</p>
    Your New Password is : {{ $newpass }} <br><br>
    Thank You <br>
    {{ Helper::csname() }}
</body>
</html>