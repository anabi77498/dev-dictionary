<header class="navbar navbar-light bg-nav-ft">
  <div class="header-left">
  <a href="/">
    <!-- THIS IMAGE / LOGO WAS CREATED BY ME, ASAD NABI (AN448)-->
    <img class="d-inline-block align-top logo" src="/public/images/logo.png" alt="website logo" height=50 width=50></a>
  <h4>Developer Dictionary</h4>
  </div>

  <div class="header-right">
    <?php if($is_home && !$is_about) { ?>
    <a class="navlink" href="/about">
      About</a>
    <?php } elseif($is_about && !$is_home) { ?>
      <a class="navlink" href="/">
      Home</a>
    <?php } else { ?>
      <a class="navlink" href="/">
      Home</a>
      <a class="navlink" href="/about">
      About</a>
    <?php } ?>

    <?php if ($activeLogin) { ?>
      <a href="/" class="login_home"><button type="button" class="btn btn-outline-primary btn-style">Home</button></a>
    <?php } else if (!is_user_logged_in()) { ?>
      <a href="/login" class="login"><button type="button" class="btn btn-outline-primary btn-style">Sign In</button></a>
    <?php } else if (is_user_logged_in()) { ?>
      <a class="logout" href="<?php echo logout_url(); ?>"><button type="button" class="btn btn-outline-primary btn-style">Sign Out</button></a>
    <?php } ?>
    </div>
</header>
