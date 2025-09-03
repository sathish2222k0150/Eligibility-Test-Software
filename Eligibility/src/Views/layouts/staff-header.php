<?
require_once __DIR__ . '/../../../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sharadha Skill Academy - Admin Panel</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link btn btn-danger text-white" href="index.php?action=logout" role="button">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php?action=staff_dashboard" class="brand-link">
     <span class="brand-text font-weight-bold" style="display:block; text-align:center; width:100%;">Staff Panel</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info w-100 text-center">
          <a href="#" class="d-block"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Staff Member'; ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php?action=staff_dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?action=manage-questions" class="nav-link">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>Manage Questions</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?action=manage-tests" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i><p>Manage Tests</p>
            </a>
          </li>
          <li class="nav-item">
          <a href="index.php?action=view-results" class="nav-link">
            <i class="nav-icon fas fa-poll"></i><p>View Results</p>
          </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">