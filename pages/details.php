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
    techs.resource_name AS 'tech.resource_name',
    techs.resource_url AS 'tech.resource_url',
    reviews.rating_mean AS 'review.rating_mean',
    reviews.rating_count AS 'review.rating_count',
    reviews.hot_yes_count AS 'review.hot_yes_count',
    reviews.hot_pct AS 'review.hot_pct',
    reviews.hot_count AS 'review.hot_count',
    techs.file_ext AS 'techs.file_ext',
    techs.file_source AS 'techs.file_source',
    tags.name AS 'tag.name'
    FROM techs
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

  $reviews_pct_hot_count =
    (string) ((float) $record['review.hot_pct'] * 100) . "%";

  $media_url = '/public/uploads/techs/' . $record["tech.id"] . '.' . $record["techs.file_ext"];
};

$tag_results = exec_sql_query($db, "SELECT tech_tags.tech_id AS 'tech_tag.tech_id',
          tech_tags.tag_id AS 'tech_tag.tag_id',
          tags.name AS 'tag.name'
          FROM tech_tags
          INNER JOIN tags ON tech_tags.tag_id = tags.id
          WHERE tech_tags.tech_id = :techsId;", array(
  ':techsId' => $record['tech.id']
));
$tag_records = $tag_results->fetchAll();

$vote_caption = '';
if ($record['review.hot_count'] == 1) {
  $vote_caption = $record['review.hot_count'] . ' vote';
} else {
  $vote_caption = $record['review.hot_count'] . ' votes';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <title>Developer Dictionary</title>

</head>

<body>
  <?php include 'includes/header.php'; ?>

  <main>

    <div class="record-name-group">
      <h1 class="record-name"><?php echo $record['tech.name'] ?></h1>
      <?php if (is_user_logged_in()) { ?>
        <form method="get" action="/details/edit">

          <!-- hidden input for what is being edited -->
          <input type="hidden" name="to_edit" value="techs.name">

          <!-- hidden input for record -->
          <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

          <button class=" edit-btn tag-edit" type="submit" aria-label="edit <?php echo htmlspecialchars($record['tech.name']) ?> name">
            <i style="font-size:22px" class="fa">&#xf044;</i>
          </button>
        </form>
      <?php } ?>
    </div>

    <div class="record-display">
      <div class="record-info1">

        <img src="<?php echo $media_url ?>" alt="<?php echo $record['tag.name'] ?> image" height=250 width=250>

        <div class="record-ratings-tags">

          <div class="tags-group">
            <?php foreach ($tag_records as $tag_record) { ?>
              <div class="badge badge-pill badge-info bg-tag-color tag-mg tag-mg-home ">
                <?php echo htmlspecialchars($tag_record["tag.name"]) ?>
              </div>
            <?php } ?>
            <?php if (is_user_logged_in()) { ?>
              <button class="edit-btn tag-edit"><i style="font-size:22px" class="fa">&#xf044;</i></button>
            <?php } ?>
          </div>

          <div class="rating-hot-group">
            <p class="rating">
              <span class="rating-logo">
                <?php echo RATING[round($record['review.rating_mean'])] ?>
              </span>
              <?php echo $record['review.rating_mean'] ?> / 5.0
            </p>

            <p class="blockquote-footer rating-p">The rating for <?php echo $record['tech.name'] ?> is based on it's usability, scalability, and overall effectiveness in the development process</p>

            <?php if ($record['review.hot_count'] > 5) { ?>
              <p class="hot-score"><span class="hot-logo">&#9832</span> <?php echo $reviews_pct_hot_count ?></p>
              <p class="hot-p blockquote-footer"><?php echo $reviews_pct_hot_count ?> of industry developers and professionals consider this to be a hot and trending technology</p>
            <?php } else { ?>
              <p class="hot-score"><span class="hot-logo">*&#9832</span> <?php echo $reviews_pct_hot_count ?></p>
              <p class="hot-p blockquote-footer">Currently, only <strong><?php echo $vote_caption ?></strong></p>
              <p class="hot-p blockquote-footer">This technology does not have enough votes to be considred hot at the moment</p>
            <?php } ?>
          </div>
        </div>
      </div>

      <div class="record-info2">
        <div class="alert alert-primary def-blurb">
          <h3>
            <?php echo $record['tech.definition'] ?>
          </h3>
          <?php if (is_user_logged_in()) { ?>
            <form method="get" action="/details/edit">

              <!-- hidden input for what is being edited -->
              <input type="hidden" name="to_edit" value="techs.definition">

              <!-- hidden input for record -->
              <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

              <button class="edit-btn definition-edit"><i style="font-size:22px" class="fa">&#xf044;</i></button>
            </form>
          <?php } ?>
        </div>


        <div class="h3-edit-group">
          <h3>Example</h3>
          <?php if (is_user_logged_in()) { ?>
            <form method="get" action="/details/edit">

              <!-- hidden input for what is being edited -->
              <input type="hidden" name="to_edit" value="techs.example">

              <!-- hidden input for record -->
              <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

              <button class="edit-btn definition-edit"><i style="font-size:22px" class="fa">&#xf044;</i></button>

            </form>
          <?php } ?>
        </div>
        <div class="bg-code rounded">
          <pre><code><?php echo $record['tech.example'] ?></pre></code>
        </div>

        <div class="h3-edit-group">
          <h3>Description</h3>

          <?php if (is_user_logged_in()) { ?>

            <form method="get" action="/details/edit">

              <!-- hidden input for what is being edited -->
              <input type="hidden" name="to_edit" value="techs.description">

              <!-- hidden input for record -->
              <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

              <button class="edit-btn definition-edit"><i style="font-size:22px" class="fa">&#xf044;</i></button>
            </form>
          <?php } ?>
        </div>
        <p><?php echo $record['tech.description'] ?></p>

        <div class="h3-edit-group">
          <h3>Resources</h3>
          <?php if (is_user_logged_in()) { ?>

            <form method="get" action="/details/edit">

              <!-- hidden input for what is being edited -->
              <input type="hidden" name="to_edit" value="techs.resource_name">

              <!-- hidden input for what is being edited -->
              <input type="hidden" name="to_edit2" value="techs.resource_url">

              <!-- hidden input for record -->
              <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

              <button class="edit-btn definition-edit"><i style="font-size:22px" class="fa">&#xf044;</i></button>
            </form>
          <?php } ?>
        </div>
        <p><a href="<?php echo $record['tech.resource_url'] ?>"><?php echo $record['tech.resource_name'] ?></a></p>
      </div>
    </div>


  </main>

  <?php include 'includes/footer.php'; ?>
</body>

</html>
