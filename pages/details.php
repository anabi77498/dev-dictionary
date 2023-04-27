<?php

const RATING = array(
  1 => '★☆☆☆☆',
  2 => '★★☆☆☆',
  3 => '★★★☆☆',
  4 => '★★★★☆',
  5 => '★★★★★'
);

// Open connection to the database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

$get_id = ($_GET['record'] == '' ? NULL : (int) $_GET['record']);

$record = NULL;

if ($get_id != NULL) {

  $result = exec_sql_query(
    $db,
    "SELECT techs.id AS 'tech.id',
    techs.name AS 'tech.name',
    techs.definition AS 'tech.definition',
    techs.example AS 'tech.example',
    techs.description AS 'tech.description',
    resources.name AS 'resource.name',
    resources.url AS 'resource.url',
    reviews.rating_mean AS 'review.rating_mean',
    reviews.rating_count AS 'review.rating_count',
    reviews.hot_yes_count AS 'review.hot_yes_count',
    reviews.hot_count AS 'review.hot_count',
    techs.file_ext AS 'techs.file_ext',
    techs.file_source AS 'techs.file_source',
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

  $mean_hot_count = round(((int) $record['review.hot_yes_count']) / ((int) $record['review.hot_count']) * 100);
  $reviews_pct_hot_count =
    (string) $mean_hot_count . "%";

  $media_url = '/public/uploads/techs/' . $record["tech.id"] . '.' . $record["techs.file_ext"];
};

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

  <?php include 'includes/header.php'; ?>

  <main>
    <cite class="logo-cite">Item was sourced at <a href="https://www.slntechnologies.com/home/image-placeholder/">slntechnologies</a></cite>

    <h1 class="record-name"><?php echo $record['tech.name'] ?></h1>

    <div class="record-display">
      <div class="record-info1">

        <img src="<?php echo $media_url ?>" alt="<?php echo $record['tag.name'] ?> image" height=250 width=250>

        <div class="record-ratings-tags">

          <p class="badge badge-pill badge-info bg-tag-color tag-mg">
            <?php echo $record['tag.name'] ?>
          </p>

          <p class="rating">
            <span class="rating-logo">
              <?php echo RATING[round($record['review.rating_mean'])] ?>
            </span>
            <?php echo $record['review.rating_mean'] ?> / 5.0
          </p>

          <p class="blockquote-footer rating-p">The rating for <?php echo $record['tech.name'] ?> is based on it's usability, scalability, and overall effectiveness in the development process</p>

          <p class="hot-score"><span class="hot-logo">&#9832</span> <?php echo $reviews_pct_hot_count ?></p>

          <p class="hot-p blockquote-footer"><?php echo $reviews_pct_hot_count ?> of industry developers and professionals consider this to be a hot and trending technology</p>
        </div>
      </div>

      <div class="record-info2">
        <h3 class="alert alert-primary def-color"><?php echo $record['tech.definition'] ?></h3>

        <h3>Example</h3>
        <div class="bg-code rounded">
          <pre><code><?php echo $record['tech.example'] ?></pre></code>
        </div>

        <h3>Description</h3>
        <p><?php echo $record['tech.description'] ?></p>

        <h3>Resources</h3>
        <p><a href="<?php echo $record['resource.url'] ?>"><?php echo $record['resource.name'] ?></a></p>
      </div>
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

  <?php include 'includes/footer.php'; ?>
</body>

</html>
