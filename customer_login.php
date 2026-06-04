<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>

    <style>

    body{
        font-family:Arial;
        background:#f5f5f5;
    }

    .login-box{
        width:400px;
        margin:100px auto;
        background:white;
        padding:40px;
        border-radius:15px;
        box-shadow:0 0 10px rgba(0,0,0,0.1);
    }

    h2{
        text-align:center;
        margin-bottom:30px;
        color:#28a745;
    }

    input{
        width:100%;
        padding:15px;
        margin-bottom:20px;
        border:1px solid #ccc;
        border-radius:8px;
    }

    button{
        width:100%;
        padding:15px;
        background:#28a745;
        color:white;
        border:none;
        border-radius:8px;
        font-size:16px;
        cursor:pointer;
    }

    </style>

</head>
<body>

<div class="login-box">

    <h2>Customer Login</h2>

    <form action="customer_login_process.php" method="POST">

        <input type="email"
               name="email"
               placeholder="Customer Email"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>
</html>