<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LORA - Monitoring backup data</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url()?>assets/css/login.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="offset-md-2 col-lg-5 col-md-7 offset-lg-4 offset-md-3 mt-5">
            <div class="panel border bg-white p-3">
                <div class="panel-heading">
                    <h3 class="pt-3 font-weight-bold">Login</h3>
                    <h5 class="pt-1 font-weight-bold">Monitoring backup data</h5>
                </div>
                <div class="panel-body p-3">
                    <form action="<?= base_url() ?>index.php/auth/act_login" method="POST">
                        <div class="form-group py-2">
                            <div class="input-field"> <span class="far fa-user p-2"></span> <input type="text" name="username" placeholder="Username or Email" required> </div>
                        </div>
                        <div class="form-group py-1 pb-2">
                            <div class="input-field"> <span class="fas fa-lock p-2"></span> <input type="password" name="password" placeholder="Enter your Password" required> </div>
                        </div>
                        <button class="btn btn-primary btn-block mt-3" type="submit" value="Login">Login</button>
                        <!-- <div class="text-center pt-4 text-muted">Don't have an account? <a href="#">Sign up</a> </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>