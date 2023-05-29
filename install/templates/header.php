<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Codoforum - install</title>

        <!--[if lte IE 8]>
         <script src="//cdnjs.cloudflare.com/ajax/libs/json2/20121008/json2.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="css/bootstrap.css" />
        <link rel="stylesheet" href="css/app.css" />
        <link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>

        <style type="text/css">

            .slogan { 
                color: white; 
                font: bold 24px/45px Helvetica, Sans-Serif; 
                letter-spacing: -1px;  
                background: rgb(0, 0, 0); /* fallback color */
                background: rgba(0, 0, 0, 0.2);
                padding: 10px; 


            }
            .slogan > img {

                margin-right: 4px;
                position: relative;
                top: -2px;    
            }

            .footnote {

                letter-spacing: 0px;  
                background: rgb(0, 0, 0); /* fallback color */
                background: rgba(0, 0, 0, 0.2);
                color: white;
                font-size: 12px;
                position: fixed;
                bottom: 0px;
                width:100%;
                left: 0px;
                padding: 5px;
            }


            .hint {

                display: none;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 50px;
                background: rgba(0,0,0,0.5);
                color: #EEE;
                text-align: center;
                font-size: 50px;
                padding: 20px;
                z-index: 100;
                opacity: 0;
                -webkit-transition: opacity 1s, -webkit-transform 0.5s 1s;
                -moz-transition: opacity 1s, -moz-transform 0.5s 1s;
                -ms-transition: opacity 1s, -ms-transform 0.5s 1s;
                -o-transition: opacity 1s, -o-transform 0.5s 1s;
                transition: opacity 1s, transform 0.5s 1s;         
            }

            .hint.active {

                opacity: 1;
                display: block;
            }

            .title {

                text-transform: uppercase;
                text-shadow: 0 3px 3px black;
                font-size: 24px;
                font-family: Hallo sans, sans;
                position: absolute;
                top: 5px;
                left: 48%;
            }

            .header-container{
                background: rgba(0, 0, 0, 0.2);
                padding-bottom: 1px;
                box-shadow: 0px 0px 20px 1px #000;
            }
        </style>

    </head>

    <body>

        <div class="header-container">
            <div class="header"><span class="slogan highlightMe"><img src="img/brand.png" />CODOFORUM</span></div>

            <div class="title">Installer</div>
        </div>
        <div class="hint">Use spacebar or arrow keys to navigate</div>