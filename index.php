<?php 
session_start();
include('config/config.php');

$dbObj = new Database($cfg);//Instantiate database
$thisPage = new WebPage($dbObj, 'webpage'); //Create new instance of webPage class
$contestObj = new Contest($dbObj);
$entrantObj = new Entrant($dbObj);
$errorArr = array();

include('includes/other-settings.php');
$thisPage->author = $cfg->author;
$thisPage->title = "Home - $thisPage->author";
$thisPage->description = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $thisPage->description; ?>">
    <meta name="author" content="">
    <title><?php echo $thisPage->title; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/heroic-features.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Sweepstakes &amp; Contest </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="<?php echo $cfg->returnUrl ? $cfg->returnUrl : "javascript:;"; ?>">Main Website</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer panel panel-default">
            <h1>A Warm Welcome!</h1>
            <p>Below is/are the available sweepstakes or contest(s) </p>
        </header>

        <hr>

        <!-- Page Features -->
        <div class="row text-center">
            <?php 
            foreach ($contestObj->fetchRaw("*", " status= 1 ", " id DESC") as $contest) {
                $contestData = array('status' => 'status', 'id' => 'id', 'title' => 'title', 'intro' => 'intro', 'description' => 'description', 'header' => 'header', 'logo' => 'logo', 'startDate' => 'start_date', 'endDate' => 'end_date', 'announcementDate' => 'announcement_date', 'winners' => 'winners', 'question' => 'question', 'answer' => 'answer', 'point' => 'point', 'bonusPoint' => 'bonus_point', 'rules' => 'rules', 'prize' => 'prize', 'message' => 'message', 'css' => 'css', 'dateAdded' => 'date_added', 'announceWinner' => 'announce_winner', 'restart' => 'restart', 'restartInterval' => 'restart_interval', 'cutOffPoint' => 'cut_off_point', 'theme' => 'theme');
                foreach ($contestData as $key => $value){
                    switch ($key) { 
                        case 'header': $contestObj->$key = MEDIA_FILES_PATH1.'contest-header/'.$contest[$value];break;
                        case 'logo': $contestObj->$key = MEDIA_FILES_PATH1.'contest-logo/'.$contest[$value];break;
                        default     :   $contestObj->$key = $contest[$value]; break; 
                    }
                }
           
            ?>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="<?php echo $contestObj->header; ?>" alt="">
                    <div class="caption">
                        <h3><?php echo $contestObj->title; ?></h3>
                        <p><?php echo StringManipulator::trimStringToFullWord(100, $contestObj->description); ?></p>
                        <p><strong>Start Date:</strong> <?php echo str_replace("-", "@", $contestObj->startDate); ?></p>
                        <p><strong>End Date:</strong> <?php echo str_replace("-", "@", $contestObj->endDate); ?></p>
                        <p>
                            <a href="<?php echo SITE_URL."contest/$contestObj->id/".StringManipulator::slugify($contestObj->title)."/"; ?>" class="btn btn-primary">Enter Now!</a> <a href="<?php echo SITE_URL."contest/$contestObj->id/".StringManipulator::slugify($contestObj->title)."/"; ?>" class="btn btn-default">More Info!</a>
                        </p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <!-- /.row -->
        <hr>
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2016 <?php echo $thisPage->author; ?></p>
                </div>
            </div>
        </footer>
    </div>
    <!-- /.container -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
