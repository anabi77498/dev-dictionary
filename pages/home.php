<?php

// Open connection to the database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

$is_filtered = FALSE;

if (isset($_GET['filter-tag'])) {
  $filter_select = $_GET['tag'];

  $is_filtered = TRUE;

  $result = exec_sql_query(
    $db,
    "SELECT techs.id AS 'tech.id',
    techs.name AS 'tech.name',
    media.tech_id AS 'media.tech_id',
    media.file_ext AS 'media.file_ext',
    reviews.hot_yes_count AS 'review.hot_yes_count',
    reviews.hot_count AS 'review.hot_count',
    tags.name AS 'tag.name'
    FROM techs
    INNER JOIN reviews ON techs.review_id = reviews.id
    INNER JOIN media ON techs.id = media.tech_id
    INNER JOIN tech_tags ON techs.id = tech_tags.tech_id
    INNER JOIN tags ON tech_tags.tag_id = tags.id
    WHERE tags.id = :tag;",
    array(":tag" => $filter_select)
  );
} else {
  $result = exec_sql_query($db, "SELECT techs.id AS 'tech.id',
  techs.name AS 'tech.name',
  media.tech_id AS 'media.tech_id',
  media.file_ext AS 'media.file_ext',
  reviews.hot_yes_count AS 'review.hot_yes_count',
  reviews.hot_count AS 'review.hot_count'
  FROM techs
  INNER JOIN reviews ON techs.review_id = reviews.id
  INNER JOIN media ON techs.id = media.tech_id;");
}

$records = $result->fetchAll();

$tag_results = exec_sql_query($db, "SELECT * FROM tags;
");
$tags = $tag_results->fetchAll();

$filtered_by = NULL;

foreach ($tags as $tag) {
  if ($tag['id'] == $filter_select) {
    $filtered_by = $tag['name'];
  }
}


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

    <h2>Welcome to the Developer Dictionary!</h2>
    <p>This is a dictionary about tricky, unecessarily complicated software engineering and computer science technologies and concepts. We simplify these terms down so that everyone can understand! </p>

    <h2>Technologies</h2>
    <section class="catalog-sect">
      <div class="catalog-queries">
        <aside>
          <div class="filter-form">
            <form class="filter-form" action="/" method="get">
              <label for="filter-tag">Filter by: </label>
              <select id="filter-tag" name="tag" required>
                <option value='' disabled selected>Select Tag</option>

                <?php foreach ($tags as $tag) { ?>
                  <option value='<?php echo $tag['id']; ?>'>
                    <?php echo $tag['name']; ?>
                  </option>
                <?php } ?>
              </select>
              <div class="filter-submit-btn">
                <button type="submit" name="filter-tag">Filter</button>
              </div>
            </form>
          </div>
          <?php if ($is_filtered) { ?>
            <p>Filter:
            <p>
            <ul class="filter-list-ul">
              <li class="filter-list">
                <span class="badge badge-pill badge-info bg-tag-color tag-mg"><?php echo $filtered_by ?> <a href="/" class="exit-filter">❌</a></span>
              </li>
            </ul>
        </aside>
      <?php } ?>
      </div>

      <div class="catalog-body">
        <?php if (count($records) > 0) { ?>
          <div class="container">
            <?php foreach ($records as $record) { ?>

              <?php
              $media_url = '/public/uploads/entries/' . $record["media.tech_id"] . '.' . $record["media.file_ext"]
              ?>

              <?php
              $tag_results = exec_sql_query($db, "SELECT tech_tags.tech_id AS 'tech_tag.tech_id',
          tech_tags.tag_id AS 'tech_tag.tag_id',
          tags.name AS 'tag.name'
          FROM tech_tags
          INNER JOIN tags ON tech_tags.tag_id = tags.id
          WHERE tech_tags.tech_id = :techsId;", array(
                ':techsId' => $record['tech.id']
              ));
              $tag_records = $tag_results->fetchAll();
              ?>

              <div>
                <form method="get" action="/details" novalidate>
                  <input type="hidden" name="record" value="<?php echo htmlspecialchars($record["tech.id"]); ?>" />
                  <button class="img-btn" type="submit" aria-label="<?php echo htmlspecialchars($record['tech.name']); ?> details page">
                    <img src="<?php echo $media_url ?>" alt="<?php echo htmlspecialchars($record['tech.name']); ?> img" height=200 width=200>
                  </button>
                  <h4><?php echo htmlspecialchars($record["tech.name"]) ?> </h4>

                  <?php foreach ($tag_records as $tag_record) { ?>
                    <p class="badge badge-pill badge-info bg-tag-color tag-mg">
                      <?php echo htmlspecialchars($tag_record["tag.name"]) ?>
                    </p>
                  <?php } ?>
                </form>
              </div>
            <?php } ?>
          </div>
        <?php } else if ($is_filtered) { ?>
          <div class="no-tags-alert">
            <p>There are no tags with this filter</p>
          </div>
        <?php } else { ?>
          <div class="no-tags-alert">
            <p>The development dictionary is <em>empty</em>. Please check back later for uploaded content</p>
          </div>
        <?php } ?>
      </div>
    </section>
  </main>
  <?php include 'includes/footer.php'; ?>
</body>

</html>
