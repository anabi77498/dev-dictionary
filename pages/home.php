<?php

// Open connection to the database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

//checking filters
$is_filtered = FALSE;
$filter_select = "";

$seed_data_ids = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

//if filtered by tag display else don't
if (isset($_GET['filter-tag'])) {
  $filter_select = $_GET['tag'];

  $is_filtered = TRUE;

  // This is the query with filtering
  $result = exec_sql_query(
    $db,
    "SELECT techs.id AS 'tech.id',
    techs.name AS 'tech.name',
    techs.file_ext AS 'tech.file_ext',
    techs.file_source AS 'tech.file_source',
    tags.name AS 'tag.name'
    FROM techs
    INNER JOIN tech_tags ON techs.id = tech_tags.tech_id
    INNER JOIN tags ON tech_tags.tag_id = tags.id
    WHERE tags.id = :tag ORDER BY techs.name ASC;",
    array(":tag" => $filter_select)
  );
} else {
  // This is the query without filtering
  $result = exec_sql_query($db, "SELECT techs.id AS 'tech.id',
  techs.name AS 'tech.name',
  techs.file_ext AS 'tech.file_ext',
  techs.file_source AS 'tech.file_source'
  FROM techs ORDER BY techs.name ASC;");
}

//fetch SQL query
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
  <!-- Citation: Styling via Bootstrap https://getbootstrap.com -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <!-- Citation: Styling via Bootstrap https://getbootstrap.com -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <!-- Citation: Icons imported from FontAwesome https://fontawesome.com -->
  <script src="https://kit.fontawesome.com/f71311d29e.js" crossorigin="anonymous"></script>
  <title>Developer Dictionary</title>

</head>

<body>

  <?php include 'includes/header.php'; ?>

  <main class="home">

    <?php if (is_user_logged_in()) { ?>
      <p>Welcome <strong><?php echo htmlspecialchars($current_user['name']); ?></strong>! You are currently logged in and able to access moderation features.</p>
    <?php } ?>

    <h2>Welcome to the Developer Dictionary!</h2>
    <p>This is a dictionary about tricky, unecessarily complicated software engineering and computer science technologies and concepts. We simplify these terms down so that everyone can understand! Our aim is to demystify jargon and technical language, providing clear explanations that empower readers to confidently navigate complex topics. Whether you're a seasoned software developer or just starting out, our dictionary is designed to help you learn and grow your understanding of the field. From obscure programming languages to advanced algorithms, we break down the most difficult concepts in a way that's accessible to all.</p>

    <p>The dictionary is constantly refined and reviewed by industry software engineers and computer scientists!</p>

    <div class="home-body">
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
                      <?php echo htmlspecialchars($tag['name']); ?>
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
                  <span class="badge badge-pill badge-info bg-tag-color tag-mg"><?php echo $filtered_by ?> </span><a href="/" class="exit-filter">❌</a></span>
                </li>
              </ul>
          </aside>
        <?php } ?>
        </div>
        <div class="catalog-sort-body">
          <!-- <div class="catalog-sort"> Sort by: </div> -->
          <div class="catalog-body">
            <?php if (count($records) > 0) { ?>
              <div class="container">
                <?php foreach ($records as $record) { ?>

                  <?php
                  $media_url = '/public/uploads/techs/' . $record["tech.id"] . '.' . $record["tech.file_ext"]
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

                  <div class="media-entry">
                    <form method="get" action="/details" novalidate>

                      <input type="hidden" name="record" value="<?php echo htmlspecialchars($record["tech.id"]); ?>" />

                      <?php if (in_array($record["tech.id"], $seed_data_ids)) { ?>
                        <div>
                          <small class="text-muted">
                            <!-- CITATION: Image was sourced from <?php echo htmlspecialchars($record['tech.file_source']) ?> -->
                            <cite>
                              Image Source: <a href="<?php echo htmlspecialchars($record['tech.file_source']) ?>">source</a>
                            </cite>
                          </small>
                        </div>
                      <?php } ?>

                      <button class="img-btn" type="submit" aria-label="<?php echo htmlspecialchars($record['tech.name']); ?> details page">

                        <img src="<?php echo $media_url ?>" alt="<?php echo htmlspecialchars($record['tech.name']); ?> img" height=200 width=200>
                      </button>

                    </form>

                    <h4><?php echo htmlspecialchars($record["tech.name"]) ?> </h4>

                    <div class="tag-display">
                      <?php if (count($tag_records) < 4) { ?>
                        <?php foreach ($tag_records as $tag_record) { ?>
                          <div class="badge badge-pill badge-info bg-tag-color tag-mg tag-mg-home ">
                            <?php echo htmlspecialchars($tag_record["tag.name"]) ?>
                          </div>
                        <?php } ?>
                      <?php } else { ?>
                        <div class="badge badge-pill badge-info bg-tag-color-2 tag-mg tag-mg-home ">
                          <?php echo count($tag_records) ?> tags
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                <?php } ?>
                <?php if (is_user_logged_in()) { ?>
                  <div class="new-entry">
                    <a href="/entry-insert">
                      <img src="/public/images/edit.png" class="insert-entry" alt="add image for media entry" height=300 width=300 />
                    </a>
                    <div class="p-cite-new-entry">
                      <p>Add a new entry</p>
                      <cite>Image taken from <a href="https://thenounproject.com/icon/add-image-396915/">The noun project</a></cite>
                    </div>
                  </div>
                <?php } ?>
              </div>
            <?php } else if ($is_filtered) { ?>
              <div class="alert alert-primary no-tags-alert" role="alert">
                <p>There are no tags associated with this filter</p>
              </div>
            <?php } else { ?>
              <div class="no-tags-alert">
                <p>The development dictionary is <em>empty</em>. Please check back later for uploaded content</p>
              </div>
            <?php } ?>
          </div>
        </div>
      </section>
    </div>
    <?php if (!is_user_logged_in()) { ?>
      <p>You are not currently logged in. If you wish to sign up and request moderation features, please email <em>an448@cornell.edu</em></p>
    <?php } ?>
  </main>
  <?php include 'includes/footer.php'; ?>
</body>

</html>
