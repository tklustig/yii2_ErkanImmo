<?php

use yii\helpers\Html;
use common\classes\KontaktAsset;

//use common\classes\KontaktAsset;
?>
<?php
KontaktAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
        <title><?= Html::encode($this->title) ?></title>
        <style>
            .absenden {
                display: inline-block;
                text-align: center;
                vertical-align: middle;
                padding: 8px 9px;
                border: 1px solid #a12727;
                border-radius: 14px;
                background: #4aff89;
                background: -webkit-gradient(linear, left top, left bottom, from(#4aff89), to(#49e615));
                background: -moz-linear-gradient(top, #4aff89, #49e615);
                background: linear-gradient(to bottom, #4aff89, #49e615);
                text-shadow: #591717 1px 1px 1px;
                font: normal normal bold 17px arial;
                color: #ffffff;
                text-decoration: none;
            }
            .absenden:hover,
            .absenden:focus {
                background: #59ffa4;
                background: -webkit-gradient(linear, left top, left bottom, from(#59ffa4), to(#58ff19));
                background: -moz-linear-gradient(top, #59ffa4, #58ff19);
                background: linear-gradient(to bottom, #59ffa4, #58ff19);
                color: #ffffff;
                text-decoration: none;
            }
            .absenden:active {
                background: #2c9952;
                background: -webkit-gradient(linear, left top, left bottom, from(#2c9952), to(#49e615));
                background: -moz-linear-gradient(top, #2c9952, #49e615);
                background: linear-gradient(to bottom, #2c9952, #49e615);
            }
            .absenden:before{
                content:  "\0000a0";
                display: inline-block;
                height: 24px;
                width: 24px;
                line-height: 24px;
                margin: 0 4px -6px -4px;
                position: relative;
                top: 0px;
                left: 0px;
                background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAEx0lEQVRIia2VfWhVdRjHP/ecc7frvXvVtbeYzusLy5fJjHxBSVkGUkynJf4ziJQCq1mJEYJJEtEfEQUGEUqiQVR/aC++FtV0c7pw0ticOt3ddvd2d7fde3ffz73nnF9/nDO9hEmDHng48OP5fV+e5+H8ZGYY9QspXlfG6ifymSNpxMdUkjPFeGQ0vVVyXIw2C6P3rAg2faR2fr61+fud2bvKHDj+F4JfGsu+E7EhISKDQkSGhIj7hBi5JHqPbbv1aS3r/1kvzZhBCFBDVgYh5gd7Ie5tH1a9cuj9306+6NyVWa7M3IMBqTDoBiBMQpMZ16JNWS806l+GIh/49l7kHIA8U/iGFWKHOz+y1KZOQnYe6BroCdCToMWxF8yVKnKiz7Zdu/PVYIyEXF1EztPlLOwOMGlKenRsmZ/e4fJ3Lo12XcbmuUD27DngKAQtDloS0jFyy+a5SoJX9G9uqL/LR7ay9+CBhh92b3hs90s1tlWbK6by1ZiI3Q4SfBjhunlsyHOwTFGwJ5MqmudPXGXlYHeCljDdCLCl/BXHzg9+Yft6O4cb3nz9EK55Jl4qSHrCYwx6vf1To4Otnr6Ri8fbROvZXjwWx2ygsqqIqlef5OXnFlFb4S6VnOv3gK5bkgTqWBeb955ersRT6AQ8ILnuC7YXzpPcs+e7qTHcNVqooW7HoDE0MnwvMuFrudkz/uvRZlqavNzYd5Fz3jDvvZvn2+dUvWBzmkPXIdthZ0ERc5WbfnpEaABbbgUI40FXJGEusSTIKiqT3MWPL0YyFq+ondq1fafPaG/vurD+cPj5z65yePNyNpYagZWgmgSGDSbbkGQU5coQt6OBcXKLgyaBTZi7lbF+5keAAUjgsAWkrMlwFeaah6W8WbeR1JXoqgke6AZ1AGRQun0M+QPhUK4aKADjAbicAc60GwMxchf/tTEiMTTAOPgMS1atLdmCMQV6GsL3QA2ABFoaQ0kYhCZDqndBsLuAnCJANl1IwsS/70RH7+3H1x5nRCV8ooOPjzZQW19XeDy/ODsHtRcS45gDgLgfhsNMKIAWiOMhOVqN4QOnCxxOsNlBsmUsqI4oBmeNgjEmAgfXyO+459sXy0oaUnfN9trNFpKCgT5CV4fwKABjUbpRqEcRQBT0KGiAJJkp28AGSh4UFsLqpVQi0iBSpstsU/h0Jm5Ch5/LkaTpgA4fHQKwKdbYFEw1imGldSZbabMSzMEbFrgBWieMDKOd7OAIYCgAl/roSajgzLMA7KZNBJCTcSZbRJn/YGERqJBuBb8HzvRz8vwtLmOV0zXKkD9CqLKUAuwgpiDQbra1sBLk5UBJBomU4UAF0QfRFggmodlP076fOWBJNAlSOqHxMN5KOwViFMa7YVgQlUGJ9uNweSCnHLKrQCoFHCA00Ccg3gNTXkgJjDP9nNr/E28bBv5pg9PvgTY1yT29i2r/OPRp+PZfYE8gRvyNdTSuLWdjSYAcpQ2UWSBbm4wG0TTGjQDdx65z5Ewn3wLhjAY+eHCGE3SNhdj+V5yu106ze2CS64DReIqWHAdL6paxafVcnipyUZyloERUQnfG6fmxkz/u+GgFJqxpPDzqqlnzST0nnFm4/7UIHNbY8wAnM3wRJSBrJhf+S/wNbS430494JFMAAAAASUVORK5CYII=") no-repeat left center transparent;
                background-size: 100% 100%;
            }
        </style>
        <?php $this->head() ?>
    </head>
    <body class="style-default button-flat layout-full-width  no-content-padding minimalist-header-no">
        <?php $this->beginBody() ?>
        <?= $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

