<?php
if (!empty($meta_title) && $meta_title != null)
    $site_title = $meta_title;
else
    $site_title = "Welcome to Luvr";
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $site_title; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
        <script src="<?php echo base_url(); ?>/assets/js/jquery-3.2.0.min.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/bootstrap.min.js"></script>
        <?php if ($sub_view == "match/nearByMatches") { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/jTinder.css">
            <script src="<?php echo base_url(); ?>/assets/js/jquery.transform2d.js"></script>
            <script src="<?php echo base_url(); ?>/assets/js/jquery.jTinder.js"></script>
        <?php } ?>
    </head>
    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */ 
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            background-color: #f1f1f1;
            height: 100%;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height:auto;} 
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {display:none;}

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" href="#">Logo</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    $user_data = $this->session->userdata('user');
                    if (empty($user_data)) {
                        ?>
                        <li>
                            <a href="<?php echo base_url() . 'user/register'; ?>"><span class="glyphicon glyphicon-log-in"></span>
                                Login
                            </a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?php echo base_url() . 'user/logout'; ?>">
                                <span class="glyphicon glyphicon-log-in"></span>
                                Logout
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">    
        <div class="row content">