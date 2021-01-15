<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="background-color: rgb(86, 99, 169) !important ">
<table style="max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse; margin-top: 50px;">
    <tr>
        <td style="background-color: #ecf0f1; text-align: center; padding: 0; border-radius: 10px 10px 0 0;">
            <a href="http://martintito.com/" style="display:flex; justify-content:center; margin-top: 30px;">
                <img style="display:block; margin: 1.5% 3%; width: 40%; height: auto;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQsQb67ugw_PuTLHs1h6kGuR2AfpOAxVBcLtQ&usqp=CAU">
            </a>
        </td>
    </tr>
    <tr>
        <td style="background-color: rgb(52,61,85); border-radius: 0 0 10px 10px;">
            <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
                <h2 style="color: rgb(221,219,0);">Hi, {{$user['name']}}</h2>
                <h3 style="text-align: center; color: rgb(234,234,234);" >You have made a purchase with the title of "{{$purchase->title}}"</h3>
                <h4 style="text-align: center; color: rgb(234,234,234);"><strong>The verification code for the purchase is:</strong></h4>
                <h1 style="word-wrap: break-word; text-align: center; color: rgb(221,219,0);"> {{$code}}</h1>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
