<?php
    session_start();
    if(isset($_SESSION['admin'])){
        header('location: admin/home.php');
    }
    if(isset($_SESSION['voter'])){
      header('location: home.php');
    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-container">
    <div class="login-box">
        <div class="login-logo">
            <b>Student Voting System</b>
        </div>
     
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your voting session</p>
            <form action="login.php" method="POST">
                <div class="form-group has-feedback">
                    <label for="voter">Voter ID</label>
                    <div class="input-with-icon">
                        <input type="text" class="form-control" id="voter" name="voter" placeholder="Enter your Voter's ID" required>
                        <i class="icon fa fa-user"></i>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label for="password">Index Number</label>
                    <div class="input-with-icon">
                        <input type="text" class="form-control" id="password" name="password" placeholder="Enter your index number" required>
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
            <p>First time voter? <a href="#">Registration Instructions</a></p>
            <p>Having trouble? <a href="#">Contact Support</a></p>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #2c6ea3;
        --primary-dark: #1e5483;
        --primary-light: #d0e4f5;
        --text-color: #333333;
        --secondary-text: #666666;
        --background-color: #f8f8f8;
        --card-color: #ffffff;
        --border-color: #dddddd;
        --error-color: #dc3545;
        --success-color: #28a745;
    }

    body {
        background: linear-gradient(135deg, #f8f9fa 0%, #d7e3f0 100%);
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
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .login-logo {
        background-color: var(--primary-color);
        color: white;
        padding: 25px 20px;
        text-align: center;
        font-size: 26px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .login-box-body {
        padding: 30px;
    }

    .login-box-msg {
        font-size: 18px;
        color: var(--secondary-text);
        margin-bottom: 25px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-size: 15px;
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
        border-radius: 6px;
        outline: none;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(44, 110, 163, 0.15);
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
        padding: 14px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn:hover {
        background-color: var(--primary-dark);
    }

    .callout {
        margin: 0 30px;
        padding: 15px;
        border-radius: 6px;
    }

    .callout-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-left: 4px solid var(--error-color);
        color: var(--error-color);
    }

    .mt20 {
        margin-top: 20px;
    }

    .login-footer {
        margin-top: 5px;
        text-align: center;
        font-size: 14px;
        color: var(--secondary-text);
        padding: 15px 30px 25px;
        border-top: 1px solid #eee;
    }

    .login-footer p {
        margin: 8px 0;
    }

    .login-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 15px;
        }
        
        .login-box-body {
            padding: 25px 20px;
        }
        
        .login-logo {
            padding: 20px;
            font-size: 22px;
        }
    }
</style>

<?php include 'includes/scripts.php' ?>
</body>
</html>