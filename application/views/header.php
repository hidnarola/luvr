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
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:400,500" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-old.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css"/>
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <?php if ($sub_view == "match/nearByMatches") { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jTinder.css">
            <script src="<?php echo base_url(); ?>assets/js/jquery.transform2d.js"></script>
            <script src="<?php echo base_url(); ?>assets/js/jquery.jTinder.js"></script>
        <?php } ?>
    </head>
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
                <a class="navbar-brand" href="<?php echo base_url(); ?>">Logo</a>
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
    <div class="container" style="min-height:600px;">    
        <div class="row content">
            <div id="msg_txt" style="display:none;"></div>