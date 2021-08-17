<?php require_once 'db_config.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>288WampProject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        body {
          margin: 0;
          font-family: "Lato", sans-serif;
        }

        .sidebar {
          margin: 0;
          padding: 0;
          width: 200px;
          background-color: #f1f1f1;
          position: fixed;
          height: 100%;
          overflow: auto;
        }

        .sidebar a {
          display: block;
          color: black;
          padding: 16px;
          text-decoration: none;
        }
         
        .sidebar a.active {
          background-color: #04AA6D;
          color: white;
        }

        .sidebar a:hover:not(.active) {
          background-color: #555;
          color: white;
        }

        div.content {
          margin-left: 200px;
          padding: 1px 16px;
          height: 1000px;
        }

        @media screen and (max-width: 700px) {
          .sidebar {
            width: 100%;
            height: auto;
            position: relative;
          }
          .sidebar a {float: left;}
          div.content {margin-left: 0;}
        }

        @media screen and (max-width: 400px) {
          .sidebar a {
            text-align: center;
            float: none;
          }
        }
    </style>
  </head>
  <body>

  <div class="sidebar">
    <a class="active" href="../../288WampProject/index.php">Search</a>
    <a href="../../288WampProject/students.php">Students</a>
    <a href="../../288WampProject/schedules.php">Schedules</a>
    <a href="../../288WampProject/courses.php">Courses</a>
  </div>

  <div class="content">