<header class="navbar navbar-light bg-nav-ft">
  <a href="/">
    <!-- THIS IMAGE / LOGO WAS CREATED BY ME, ASAD NABI (AN448)-->
    <img class="d-inline-block align-top logo" src="/public/images/logo.png" alt="website logo" height=75 width=75>
  </a>
  <h1 class="title">The Developer Dictionary</h1>
  <?php if ($activeLogin) { ?>
    <a href="/" class="login_home"><button>Home</button></a>
  <?php } else if (!is_user_logged_in()) { ?>
    <a href="/login" class="login"><button>Sign In</button></a>
  <?php } else if (is_user_logged_in()) { ?>
    <a class="logout" href="<?php echo logout_url(); ?>"><button>Sign Out</button></a>
  <?php } ?>
</header>
