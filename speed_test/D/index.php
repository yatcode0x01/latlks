<?php
$path = 'images/';
$completeCaptcha = false;

function fetchAllImages($path) {
    $images = array();
    if ( $img_dir = @opendir($path) ) {
        while (false !== ($img_file = readdir($img_dir))) {
            if (preg_match("/(\.gif|\.jpg|\.png|\.jpeg)$/", $img_file) ) {
                $images[] = $img_file;
            }
        }
        closedir($img_dir);
    }
    return $images;
}

function randomImages($ar) {
    mt_srand((double)microtime() * 1000000);
    $num = array_rand($ar);
    return $ar[$num];
}

$files = fetchAllImages($path);

$file = randomImages($files);
$filename = preg_replace('/(.*)\\.[^\\.]*/', '$1', $file);

if (isset($_POST['submit'])) {
    $captcha = md5($_POST['captcha']);
    if ($captcha == $filename) {
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
    <?php if ($completeCaptcha == false) { ?>
        <div class="captcha">
            <img src="<?php echo $path .'/'. $file; ?>">
            <form class="form-fields" method="post" action="">
                <label>Catcha Value</label>
                <input type="text" name="captcha"/>
                <div class="text-center">
                    <button type="submit" name="submit">Complete</button>
                </div>
            </form>
        </div>
    <?php } ?>
    <?php if ($completeCaptcha == true) { ?>
        <div class="captcha">
            You was complete this captcha.
        </div>
    <?php } ?>
</body>
</html>