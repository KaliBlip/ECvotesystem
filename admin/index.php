<?php
    session_start();
    if(isset($_SESSION['admin'])){
        header('location:home.php');
    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-container">
    <div class="login-box">
        <div class="login-logo">
            <b>Admin Voting Dashboard</b>
        </div>
     
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your admin session</p>
            <form action="login.php" method="POST">
                <div class="form-group has-feedback">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                        <i class="icon fa fa-user"></i>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <i class="icon fa fa-lock"></i>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="login">
                        <i class="fa fa-sign-in"></i> Sign In
                    </button>
                </div>
            </form>
        </div>
        <?php
            if(isset($_SESSION['error'])){
                echo "
                    <div class='callout callout-danger text-center mt20'>
                        <p>".$_SESSION['error']."</p>
                    </div>
                ";
                unset($_SESSION['error']);
            }
        ?>
        <div class="login-footer">
            <p>Having trouble? <a href="#">Contact Support</a></p>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #1a73e8;
        --primary-dark: #1557b0;
        --text-color: #202124;
        --secondary-text: #5f6368;
        --background-color: #f5f7fa;
        --card-color: #ffffff;
        --border-color: #dadce0;
        --error-color: #d93025;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        height: 100vh;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        width: 100%;
        max-width: 450px;
        padding: 20px;
    }

    .login-box {
        background-color: var(--card-color);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .login-logo {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 24px;
    }

    .login-box-body {
        padding: 30px;
    }

    .login-box-msg {
        font-size: 16px;
        color: var(--secondary-text);
        margin-bottom: 25px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-size: 14px;
        font-weight: 500;
    }

    .input-with-icon {
        position: relative;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        font-size: 16px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        outline: none;
        transition: border 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.1);
    }

    .icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-text);
    }

    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: var(--primary-dark);
    }

    .callout {
        margin-top: 20px;
        padding: 15px;
        border-radius: 4px;
    }

    .callout-danger {
        background-color: rgba(217, 48, 37, 0.1);
        border-left: 4px solid var(--error-color);
        color: var(--error-color);
    }

    .login-footer {
        margin-top: 10px;
        text-align: center;
        font-size: 14px;
        color: var(--secondary-text);
        padding: 10px 20px 20px;
    }

    .login-footer a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 10px;
        }
        
        .login-box-body {
            padding: 20px;
        }
    }
</style>

<?php include 'includes/scripts.php' ?>
</body>
</html>