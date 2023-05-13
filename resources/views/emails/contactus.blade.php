<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table width="100%" border="1" cellspacing="0">
        <tbody>
            <tr>
                <th>Name</th>
                <td>{{ $request->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $request->email }}</td>
            </tr>
            <tr>
                <th>Subject</th>
                <td>{{ $request->subject }}</td>
            </tr>
            <tr>
                <th>Messge</th>
                <td>{{ $request->message }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>