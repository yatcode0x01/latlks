<?php
session_start();
$completeCaptcha = false;

if (isset($_POST['submit'])) {
    $correctCaptcha = $_SESSION['captcha'];
    $inputCaptcha = $_POST['captcha'];
    
    if ($inputCaptcha == $correctCaptcha) {
        $completeCaptcha = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captcha</title>
</head>
<body>
    <style type="text/css">
        :root {
            --light: #f1f2f6;
        }

        * {
            margin: 0;
            padding: 0;
        }

        img {
            width: 150px;
            height: 50px;
        }
        
        .captcha {
            text-align: center;
            margin-top: 20%;
        }

        label {
            display: block;
        }

        input {
            border-style: none;
            padding: 8px 10px 8px 10px;
            background: var(--light);
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px solid grey;
            border-radius: 10px;
        }

        button {
            border-style: none;
            padding: 8px 25px 8px 25px;
            background: var(--light);
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px solid grey;
            border-radius: 10px;
            cursor: pointer;
        }
    </style>
    <?php
        
        if ($completeCaptcha == true) { 
    ?>
      <div class="captcha">
            Captcha is complete!
        </div>  
    <?php } else { ?>
        <div class="captcha">
            <img src="captcha.php" alt="image">
            <form class="form-fields" method="post" action="">
                <label>Catcha Value</label>
                <input type="text" name="captcha"/>
                <div class="text-center">
                    <button type="submit" name="submit">Complete</button>
                </div>
            </form>
        </div>
    <?php } ?>
</body>
</html>