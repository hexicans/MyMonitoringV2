<meta charset="utf-8">
<?php
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: login.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
  <head>

<link rel="icon" type="image/png" href="favicon.png" />
    <meta charset="UTF-8">
    <title>MyMonitor</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>MyMonitor</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>MyMonitor</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="https://lh3.googleusercontent.com/-vI0a4fkM_Ak/AAAAAAAAAAI/AAAAAAAAAAA/7yZgNUb5dGM/photo.jpg" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"> <?php echo htmlentities(trim($_SESSION['login'])); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="https://lh3.googleusercontent.com/-vI0a4fkM_Ak/AAAAAAAAAAI/AAAAAAAAAAA/7yZgNUb5dGM/photo.jpg" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo htmlentities(trim($_SESSION['login'])); ?>
											<br />
											<a href="profil.php" style="color:white;">Mon profil</a>
                    </p>
                  </li>
                  <!-- Menu Body -->

                  <!-- Menu Footer-->

                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="logout.php"><i class="fa fa-power-off"></i></a>
              </li>
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="https://lh3.googleusercontent.com/-vI0a4fkM_Ak/AAAAAAAAAAI/AAAAAAAAAAA/7yZgNUb5dGM/photo.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo $_SESSION['login']; ?></p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
        <!--
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
-->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <li class="treeview">
              <a href="index.php">
                <i class="fa fa-dashboard"></i>
                <span>Accueil</span>
              </a>
            <li class="treeview">
              <a href="myservers.php">
                <i class="glyphicon glyphicon-tasks"></i>
                <span>Mes serveurs</span>




              </a>
            </li>
            <li>
              <a href="addserver.php">
                <i class="glyphicon glyphicon-share-alt"></i> <span>Ajouter un serveur</span>
              </a>
            </li>




      </aside>
