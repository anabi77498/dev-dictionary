<?php

// Open connection to the database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Developer Dictionary</title>

</head>

<header>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=50 width=50>
  <h1>The Developer Dictionary</h1>
  <cite>Item was sourced at <a href="https://www.slntechnologies.com/home/image-placeholder/">slntechnologies</a></cite>
</header>

<body>
  <p>Welcome to the developer dictionary!</p>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>
  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>



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
</body>

</html>
