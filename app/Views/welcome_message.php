<?php 

function imageToBase64($path) {
    $path = $path;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Satisfy&display=swap" rel="stylesheet">
    <style>
       
        body{
            font-family: 'comic-sans';
        }
        .container{
            width: 300px;
            margin: 0 auto;
        }
        .card{
            border:1px solid gray;
            padding: 10px;
        }
        .card-image{
            width: 300px;
        }
        



    </style>
</head>
<body style="color: gray">

<?php 
    foreach($list as $index=>$item){        
?>


<div class="container">
      <div class="card">
        <div class="card-image">
          <img  width="275" src='<?= imageToBase64($item["pr_img"])?>' />
        </div>
        
        <div class="card-content">
          <table  style="width: 275px;">
            <tr>
                <td style="font-size: 24px; font-family: 'Satisfy', cursive"><?= $item["pr_name"]?></td>
                <td style="font-size: 24px; font-family: 'Satisfy', cursive" align="right"><?=$item["pr_price"]?></td>
            </tr>
          </table>
          
        </div>
        
      </div>

    <br><br>
<?php
    }
?>
</div>
    
    
  

</body>
</html>
