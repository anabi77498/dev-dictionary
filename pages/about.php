<?php
$is_home = False;
$is_about = True;
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" />
  <!-- Citation: Styling via Bootstrap https://getbootstrap.com -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <!-- Citation: Styling via Bootstrap https://getbootstrap.com -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <!-- Citation: Icons imported from FontAwesome https://fontawesome.com -->
  <script src="https://kit.fontawesome.com/f71311d29e.js" crossorigin="anonymous"></script>

  <link rel="icon" href="../public/images/logo.png" type="image/icon type">
  <title>Developer Dictionary</title>

</head>

<body>

  <?php include 'includes/header.php'; ?>

  <main class="about">
  <!-- <h2>About the Developer Dictionary!</h2> -->

  <div class="jumbotron">
  <div class="container">
    <h1 class="display-4"> Technology is changing the world and everyone should be apart of the discussion </h1>


    <p class="lead">We all deserve to talk about what's happening in technology and how it is impacting us but many common terms and phrases are mistified by complex technical jargon that's difficult to digest. </p>


    <p class="lead">This is a dictionary about tricky, unecessarily complicated software engineering and computer science technologies and concepts. We simplify these terms down so that everyone can understand! Our aim is to demystify jargon and technical language, providing clear explanations that empower readers to confidently navigate complex topics. Our dictionary is designed to help you learn and grow your understanding of the field. From obscure programming languages to advanced algorithms, we break down the most difficult concepts in a way that's accessible to all.</p>

    <p class="lead">Whether you're a project manager looking to understand the complexities of your team or a supervisor who wants to be hip to the latest technologies, the developer dictionary ensures that you can effectively conversate and contribute in technical discussions </p>

    <p class="lead">The dictionary is constantly refined and reviewed for accuracy. It is an open-source project. Please login to make changes to part's of the dictionary</p>
  </div>
</div>

  </main>
  <?php include 'includes/footer.php'; ?>
</body>

</html>
