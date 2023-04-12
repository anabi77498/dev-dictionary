<?php

// Open connection to the database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

$get_id = ($_GET['record'] == '' ? NULL : (int) $_GET['record']);

$record = NULL;

if ($get_id != NULL) {
  //change this result eventually to include tags

  // $result = exec_sql_query(
  //   $db,
  //   "SELECT techs.id AS 'techs.id',
  //   techs.name AS 'techs.name',
  //   techs.definition AS 'techs.definition',
  //   techs.example AS 'techs.example',
  //   techs.description AS 'techs.description',
  //   resources.name AS 'resources.name',
  //   resources.url AS 'resources.url',
  //   reviews.rating_mean AS 'reviews.rating_mean',
  //   reviews.rating_count AS 'reviews.rating_count',
  //   reviews.hot_yes_count AS 'reviews.hot_yes_count',
  //   reviews.hot_count AS 'reviews.hot_count'
  // FROM techs
  // INNER JOIN resources ON techs.resource_id = resources.id
  // INNER JOIN reviews ON techs.review_id = reviews.id;
  // "
  // );
  // $records = $result->fetchAll();

  $result = exec_sql_query(
    $db,
    "SELECT techs.name AS 'tech.name',
    techs.definition AS 'tech.definition',
    techs.example AS 'tech.example',
    techs.description AS 'tech.description',
    resources.name AS 'resource.name',
    resources.url AS 'resource.url',
    reviews.rating_mean AS 'review.rating_mean',
    reviews.rating_count AS 'review.rating_count',
    reviews.hot_yes_count AS 'review.hot_yes_count',
    reviews.hot_count AS 'review.hot_yes_count',
    tags.name AS 'tag.name'
    FROM techs
    INNER JOIN resources ON techs.resource_id = resources.id
    INNER JOIN reviews ON techs.review_id = reviews.id
    INNER JOIN tech_tags ON techs.id = tech_tags.tech_id
    INNER JOIN tags ON tech_tags.tag_id = tags.id
    WHERE techs.id = :techsId;
    ",
    array(
      ':techsId' => $get_id
    )
  );
  $records = $result->fetchAll();


  if (count($records) > 0) {
    $record = $records[0];
  };
};

// $result = exec_sql_query(
//   $db,
//   "SELECT techs.name, techs.definition, techs.example, techs.description, reviews.rating_mean, reviews.rating_count, reviews.hot_yes_count, reviews.hot_count, resource.url
//   FROM techs
//   INNER JOIN resources ON techs.resource_id = resources.id
//   INNER JOIN reviews ON techs.review_id = reviews.id;"
// );

// get records from query
// $records = $result->fetchAll();

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

  <h1>ID received from GET: <?php echo $get_id ?></h1>

  <h1>Number of records pulled from DB: <?php echo (string) count($records) ?></h1>

  <h1>Name: <?php echo $record['tech.name'] ?> </h1>

  <img src="/public/images/placeholder.jpg" alt="placeholder img" height=200 width=200>

  <p><?php echo $record['tech.definition'] ?></p>

  <p><?php echo $record['tech.example'] ?></p>

  <p><?php echo $record['tech.description'] ?></p>

  <p><?php echo $record['resource.name'] ?></p>

  <p><?php echo $record['resource.url'] ?></p>

  <p><?php echo $record['resource.url'] ?></p>

  <p><?php echo $record['tag.name'] ?></p>


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
