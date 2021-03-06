<?php 
session_start(); 
include('../config/config.php');
$dbObj = new Database($cfg);//Instantiate database
$thisPage = new WebPage($dbObj); //Create new instance of webPage class

$contestObj = new Contest($dbObj); // Create an object of Contest class
$errorArr = array(); //Array of errors
//get the contest id; if failed redirect to contest-categories page
$thisContestId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : $thisPage->redirectTo('manage-contests');
//Check if the contest exists if not redirect it to manage-contests page
if(count($contestObj->fetchRaw("*", " id = $thisContestId "))<1){$thisPage->redirectTo('manage-contests');}

foreach ($contestObj->fetchRaw("*", " id = $thisContestId ") as $contest) {
    $contestData = array('status' => 'status', 'id' => 'id', 'title' => 'title', 'intro' => 'intro', 'description' => 'description', 'header' => 'header', 'logo' => 'logo', 'startDate' => 'start_date', 'endDate' => 'end_date', 'announcementDate' => 'announcement_date', 'winners' => 'winners', 'question' => 'question', 'answer' => 'answer', 'point' => 'point', 'bonusPoint' => 'bonus_point', 'rules' => 'rules', 'prize' => 'prize', 'message' => 'message', 'css' => 'css', 'dateAdded' => 'date_added', 'announceWinner' => 'announce_winner', 'restart' => 'restart', 'restartInterval' => 'restart_interval', 'cutOffPoint' => 'cut_off_point', 'theme' => 'theme');
    foreach ($contestData as $key => $value){ switch ($key) { default     :   $contestObj->$key = $contest[$value]; break;  } }
}
$cfg->templateName = $contestObj->theme ? $contestObj->theme : 'default';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="Sweepstakes, Contest">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Edit <?php echo $contestObj->title; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <script src="assets/ckeditor/ckeditor.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/gritter/css/jquery.gritter.css" />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <section id="container" class="">
        <?php include('includes/header.php'); ?>
        <?php include('includes/sidebar.php'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div id="messageBox"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="breadcrumb pull-left text-primary">
                            <li> <i class="fa fa-cogs"></i> Sweepstakes Settings</li>
                        </ul>
                        <!--breadcrumbs start -->
                        <ul class="breadcrumb pull-right text-danger">
                            <li> <i class="fa fa-eye"></i> Output Preview </li>
                        </ul>
                        <!--breadcrumbs end -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <section class="panel panel-primary">
                          <header class="panel-heading">
                              Sweepstakes Editing Wizard
                          </header>
                          <div class="panel-body">
                              <div class="stepy-tab">
                                  <ul id="default-titles" class="stepy-titles clearfix">
                                      <li id="default-title-0" class="current-step">
                                          <div>Step 1</div>
                                      </li>
                                      <li id="default-title-1" class="">
                                          <div>Step 2</div>
                                      </li>
                                      <li id="default-title-2" class="">
                                          <div>Step 3</div>
                                      </li>
                                  </ul>
                              </div>
                              <form class="form-horizontal" action="../REST/manage-contests.php" id="default" method="POST"  enctype="multipart/form-data">
                                  <fieldset title="Step1" class="step" id="default-step-0">
                                      <legend> </legend>
                                      <div class="form-group">
                                            <label class="col-lg-2 control-label" for="theme">Theme:</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="theme" id="theme">
                                                <?php 
                                                if(count(Folder::getSubDirectories(TEMPLATE_LOCATION))>0){
                                                    foreach(Folder::getSubDirectories(TEMPLATE_LOCATION) as $template){
                                                ?>
                                                    <option <?php echo $contestObj->theme == $template ? 'selected="selected"' : ''; ?> value="<?php echo $template; ?>"> <?php echo $template; ?></option>
                                                <?php  }} ?>
                                                </select>
                                            </div>
                                        </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="title"> Title:</label>
                                          <div class="col-lg-10 input-preview">
                                              <input type="hidden" name="id" id="id" value="<?php echo $contestObj->id; ?>"/>
                                              <input value="<?php echo $contestObj->title; ?>" type="text" data-preview-id="prevTitle" size="100" id="title" name="title" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="This value will appear as the contest's page title" placeholder="Sweepstakes Title">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="intro"> Intro:</label>
                                          <div class="col-lg-10 input-preview">
                                              <input value="<?php echo $contestObj->intro; ?>" type="text" size="50" data-preview-id="prevIntro" name="intro" id="intro" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Brief introduction to the sweepstakes." placeholder="Sweepstakes Intro Text">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="description"> Description:</label>
                                          <div class="col-lg-10 input-preview">
                                              <textarea data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="This description will be used as the sweepstakes' page description as well as brief description to appear on the page" data-preview-id="prevDescription" id="description" name="description" placeholder="About this sweepstakes or contest"><?php echo $contestObj->description; ?></textarea>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Header:</label>
                                          <div class="col-lg-10">
                                              <img class="hidden" id="oldHeaderImg" src="<?php echo MEDIA_FILES_PATH1.'contest-header/'.$contestObj->header; ?>" style="width:60px;height:20px">
                                              <input type="hidden" id="oldHeader" name="oldHeader" value="<?php echo $contestObj->header; ?>" />
                                              <input type="file" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="The header image in the template will be replaced by the supplied here" name="header" id="header">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Logo:</label>
                                          <div class="col-lg-10">
                                              <img class="hidden" id="oldLogoImg" src="<?php echo MEDIA_FILES_PATH1.'contest-logo/'.$contestObj->logo; ?>" style="width:60px;height:20px">
                                              <input type="hidden" id="oldLogo" name="oldLogo" value="<?php echo $contestObj->logo; ?>"/>
                                              <input type="file" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="This will replace the small image caption below the header image" name="logo" id="logo">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="startDate"> Start:</label>
                                          <div class="col-lg-10">
                                                <div class="input-group date form_datetime-adv" data-date="<?php echo $contestObj->startDate; ?>">
                                                    <input value="<?php echo $contestObj->startDate; ?>" type="text" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="The date at which the contest/sweepstakes will start to appear" data-preview-id="prevStartDate" id="startDate" name="startDate" readonly="" size="16" placeholder="Start Date">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-danger date-reset"><i class="fa fa-times"></i></button>
                                                        <button type="button" class="btn btn-warning date-set"><i class="fa fa-calendar"></i></button>
                                                    </div>
                                                </div>  
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="endDate"> End:</label>
                                          <div class="col-lg-10">
                                              <div class="input-group date form_datetime-adv" data-date="<?php echo $contestObj->endDate; ?>">
                                                  <input value="<?php echo $contestObj->endDate; ?>" type="text" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="The date and time at which contest/sweepstakes will stop appearing or ends" data-preview-id="prevEndDate" id="endDate" name="endDate" readonly="" size="16" placeholder="End Date">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-danger date-reset"><i class="fa fa-times"></i></button>
                                                        <button type="button" class="btn btn-warning date-set"><i class="fa fa-calendar"></i></button>
                                                    </div>
                                                </div>  
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="announcementDate"> Winner:</label>
                                          <div class="col-lg-10">
                                              <div class="input-group date form_datetime-adv" data-date="<?php echo $contestObj->announcementDate; ?>">
                                                  <input value="<?php echo $contestObj->announcementDate; ?>" type="text" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="The winner(s) details will appear on this day." data-preview-id="prevAnnounceDate" id="announcementDate" name="announcementDate" readonly="" size="16" placeholder="Winner Announcement Date">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-danger date-reset"><i class="fa fa-times"></i></button>
                                                        <button type="button" class="btn btn-warning date-set"><i class="fa fa-calendar"></i></button>
                                                    </div>
                                                </div>  
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="winners"> No of Winners:</label>
                                          <div class="col-lg-10 input-preview">
                                              <input value="<?php echo $contestObj->winners; ?>" type="number" data-preview-id="prevNoOfWinners" id="winners" name="winners" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="The number of expected winners in the contest" placeholder="Number of Winners">
                                          </div>
                                      </div>
                                  </fieldset>
                                  <fieldset title="Step 2" class="step" id="default-step-1" >
                                      <legend> </legend>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for='question'>Question:</label>
                                          <div class="col-lg-10 input-preview">
                                              <input value="<?php echo $contestObj->question; ?>" type="text" data-preview-id="prevBonusQuestion" id="question" name="question" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Additional bonus question that will be answered once after which it will not be counted" placeholder="Bonus Question">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="answer">Answer:</label>
                                          <div class="col-lg-10 input-preview">
                                              <input value="<?php echo $contestObj->answer; ?>" type="text" data-preview-id="prevBonusAnswer" name="answer" id="answer" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Answer to the bonus question" placeholder="Answer to bonus question">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="bonusPoint">Bonus Point:</label>
                                          <div class="col-lg-10 input-preview">
                                              <input data-preview-id="prevBonusPoint" value="<?php echo $contestObj->bonusPoint; ?>" type="text" name="bonusPoint" id="bonusPoint" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Point to be earned for supplying the correct answer to the bonus question" placeholder="Bonus Point">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="point">Points per Invitation:</label>
                                          <div class="col-lg-10">
                                              <input value="<?php echo $contestObj->point; ?>" type="text" name="point" id="point" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Point to be earned for each friend invitation" placeholder="Point per invitation">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="cutOffPoint">Cutoff Point:</label>
                                          <div class="col-lg-10">
                                              <input value="<?php echo $contestObj->cutOffPoint; ?>" type="text" name="cutOffPoint" id="cutOffPoint" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Minimum points required to be attained to emerge the winner" placeholder="Minimum point required">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label" for="rules">Rules:</label>
                                          <div class="col-lg-10 input-preview">
                                              <textarea data-placement="top" class="form-control tooltips ckeditor" data-toggle="tooltip" data-original-title="Rules and regulation binding the contest" id="rules" placeholder="Rules" name="rules" data-preview-id="prevRules" cols="60" rows="5"><?php echo $contestObj->rules; ?></textarea>
                                          </div>
                                      </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label" for="prize">Prize:</label>
                                            <div class="col-lg-10 input-preview">
                                                <textarea data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="The details of the prize to be won by the winners and how to redeem the prize" id="prize" placeholder="Prize to be won" name="prize" data-preview-id="prevPrize" cols="60" rows="5"><?php echo $contestObj->prize; ?></textarea>
                                            </div>
                                        </div>
                                       <div class="form-group">
                                            <label class="col-lg-2 control-label" for="message">Message:</label>
                                            <div class="col-lg-10 input-preview">
                                                <textarea data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Message that will appear after each successful invitation" id="message" placeholder="Thank you message" name="message" cols="60" rows="5"><?php echo $contestObj->message; ?></textarea>
                                            </div>
                                        </div>
                                  </fieldset>
                                  <fieldset title="Step 3" class="step" id="default-step-2" >
                                      <legend> </legend>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label" for="announceWinner">Auto Announce Winner:</label>
                                            <div class="col-lg-10">
                                                <select data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="YES valu will allow the winner to appear automatically after the contest has ended. NO does otherwise" name="announceWinner" id="announceWinner">
                                                    <option <?php echo $contestObj->announceWinner == 'Yes' ? 'selected="selected"' : ''; ?> value="Yes">Yes</option>
                                                    <option <?php echo $contestObj->announceWinner == 'No' ? 'selected="selected"' : ''; ?> value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label" for="restart">Auto Restart:</label>
                                            <div class="col-lg-10">
                                                <select data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="YES will automatically restart the contest after the contest has expired and after the interval specified default is 7 days. No does nothing." name="restart" id="restart">
                                                    <option <?php echo $contestObj->restart == 'Yes' ? 'selected="selected"' : ''; ?> value="Yes">Yes</option>
                                                    <option <?php echo $contestObj->restart == 'No' ? 'selected="selected"' : ''; ?> value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                      <div class="form-group">
                                            <label class="col-lg-2 control-label" for="restartInterval">Restart Interval (days):</label>
                                            <div class="col-lg-10">
                                                <input value="<?php echo $contestObj->restartInterval; ?>" type="number"  data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="This field has no effect if Autorestart value is NO " name="restartInterval" id="restartInterval" value="7"/>
                                            </div>
                                        </div>
                                      <div class="form-group">
                                            <label class="col-lg-2 control-label" for="css">Custom CSS:</label>
                                            <div class="col-lg-10 input-preview">
                                                <textarea data-preview-id="customStyles" data-placement="top" class="form-control tooltips" data-toggle="tooltip" data-original-title="Additional custom styles for customizing further the template and should be entered without <style></style> tags" id="css" placeholder="Custom CSS" name="css" cols="60" rows="5"><?php echo $contestObj->css; ?></textarea>
                                            </div>
                                        </div>
                                  </fieldset>
                                  <input type="hidden" id="editContest" name="editContest" value="editContest"/>
                                  <input type="submit" class="finish btn btn-danger" value="Update"/>
                              </form>
                          </div>
                      </section>
                    </div>
                    <div class="col-lg-6">
                        <section class="panel panel-info">
                            <header class="panel-heading" id="prevTitle">
                                New Sweepstakes or Contest
                            </header>
                            <div class="panel-body">
                                <div id="previewpane"></div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="messageBox"></div>
            </section>
        </section>
        <!--main content end-->
        <?php include('includes/footer.php'); ?>
    </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/gritter/js/jquery.gritter.js"></script>
    <script src="js/respond.min.js" ></script>
    <!--common script for all pages-->
    <script src="js/common-scripts.js"></script>
    <!--script for this page only-->
    <script type="text/javascript" src="assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <!--script for this page-->
    <script src="js/jquery.stepy.js"></script>
    <script src="js/add-contest.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $('#previewpane').load('<?php echo SITE_URL.'load-template?name='.$cfg->templateName; ?>');
            $("#header").change(function(){ readURL(this, 'img#oldHeaderImg'); });
            $("#logo").change(function(){ readURL(this, 'img#oldLogoImg'); });
            //Set Preview after DOM elements has loaded
            setInterval(function(){
                $("img#headerImage").attr('src', $("img#oldHeaderImg").attr('src'));
                $("img#logoImage").attr('src', $("img#oldLogoImg").attr('src'));
                $('.form_datetime-adv input, .input-preview input, .input-preview textarea').each(function(){
                    $('#'+$(this).attr('data-preview-id')).html($(this).val() ? $(this).val() : $(this).text());
                });
            }, 2000);
            $("#theme").change(function () {
                $('#previewpane').load('<?php echo SITE_URL.'load-template?name='; ?>'+$(this).val());
            });            
        });
    </script>
  </body>
</html>
