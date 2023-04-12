<?php

// Open connection to the database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

$result = exec_sql_query($db, 'SELECT * FROM techs;');
$records = $result->fetchAll();
?>


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

  <header class="navbar navbar-light bg-nav">
    <a href="/">
      <img class="d-inline-block align-top logo" src="/public/images/placeholder.jpg" alt="placeholder img" height=50 width=50>
    </a>
    <h1 class="title">The Developer Dictionary</h1>
    <button class="login">login</button>
  </header>

  <main>
    <cite class="logo-cite">Item was sourced at <a href="https://www.slntechnologies.com/home/image-placeholder/">slntechnologies</a></cite>
    <h1><?php echo $record['tech.name'] ?></h1>

    <p>Welcome to the developer dictionary!</p>

    <h2>Technologies</h2>

    <div class="container">
      <?php foreach ($records as $record) { ?>
        <div>
          <form method="get" action="/details" novalidate>
            <input type="hidden" name="record" value="<?php echo htmlspecialchars($record["id"]); ?>" />
            <button class="img-btn" type="submit" aria-label="<?php echo htmlspecialchars($record['name']); ?> details page">
              <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>
            </button>
            <p><?php echo htmlspecialchars($record["name"]) ?> </p>
          </form>
        </div>
      <?php } ?>
    </div>





    <h2>Form</h2>
    <form id="form" action="/home" method="post" novalidate>
      <div>
        <label for="input-1">Label 1</label>
        <input type="text" id="input-1" name="input-name-1">
      </div>

      <div>
        <label for="input-2">Label 2</label>
        <input type="text" id="input-2" name="input-name-2">
      </div>

      <div>
        <label for="input-3">Label 3</label>
        <input type="text" id="input-3" name="input-name-3">
      </div>
    </form>
  </main>
</body>

</html>
