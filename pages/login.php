<?php $activeLogin = True ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <title>Developer Dictionary</title>

</head>

<body>

  <?php include 'includes/header.php'; ?>

  <main>

    <?php if (!is_user_logged_in()) { ?>
      <h1>Sign In</h1>
      <?php echo login_form('/login', $session_messages); ?>
    <?php } ?>
    <?php if (is_user_logged_in()) { ?>
      <div class="alert alert-success" role="alert">
        <p class="success-login-msg"><strong>Success!</strong> You are currently logged in</p>
      </div>
      <p>Please navigate back to the <strong><a href="/" class="login_home">Home Page</a></strong></p>
    <?php } ?>
  </main>
  <?php include 'includes/footer.php'; ?>
</body>

</html>
