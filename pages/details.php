<?php
$is_home = False;
$is_about = False;

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

$seed_data_ids = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

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
    reviews.id AS 'review.id',
    reviews.rating_mean AS 'review.rating_mean',
    reviews.rating_count AS 'review.rating_count',
    reviews.hot_yes_count AS 'review.hot_yes_count',
    reviews.hot_pct AS 'review.hot_pct',
    reviews.hot_count AS 'review.hot_count',
    techs.file_ext AS 'tech.file_ext',
    techs.file_source AS 'tech.file_source',
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

  $media_url = '/public/uploads/techs/' . $record["tech.id"] . '.' . $record["tech.file_ext"];

  $plain_url = 'public/uploads/techs/' . $record["tech.id"] . '.' . $record["tech.file_ext"];
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


$show_edits = False;
$edits_on = (bool) $_GET['edits_on'];
if ($edits_on) {
  $show_edits = True;
}

if (isset($_GET['delete_entry']) && is_user_logged_in()) {

  $del_id = $_GET['delete_record'];
  $del_rev_id = $_GET['delete_review_record'];
  $del_media = $_GET['delete_media'];
  unlink($del_media);


  $delete_query_techs = exec_sql_query($db, "DELETE FROM techs WHERE id = :tech_id;", array(
    ':tech_id' => $del_id
  ));

  $delete_query_reviews = exec_sql_query($db, "DELETE FROM reviews WHERE id = :review_id;", array(
    ':review_id' => $del_rev_id
  ));

  $delete_query_tech_tag = exec_sql_query($db, "DELETE FROM tech_tags WHERE tech_id = :techId", array(
    ':techId' => $del_id
  ));

  $header_val = 'Location: ' . '/';

  if ($delete_query_techs && $delete_query_reviews) {
    header($header_val);
  };
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
  <!-- Citation: Icons imported from FontAwesome
  https://fontawesome.com -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Citation: Styling via Bootstrap https://getbootstrap.com -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <!-- Citation: Icons imported from FontAwesome https://fontawesome.com -->
  <script src="https://kit.fontawesome.com/f71311d29e.js" crossorigin="anonymous"></script>
  <link rel="icon" href="../public/images/logo.png" type="image/icon type">
  <title>Developer Dictionary</title>

</head>

<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <div class="details-top">
      <div class="record-name-group">
        <h1 class="record-name"><?php echo $record['tech.name'] ?></h1>
        <?php if (is_user_logged_in() && $show_edits) { ?>
          <form method="get" action="/details/edit">

            <!-- hidden input for what is being edited -->
            <input type="hidden" name="to_edit" value="techs.name">

            <!-- hidden input for record -->
            <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

            <button class="edit-btn tag-edit" type="submit" aria-label="edit <?php echo htmlspecialchars($record['tech.name']) ?> name">
              <i style="font-size:22px" class="fa" id="edit-btn-id">&#xf044;</i>
            </button>
          </form>
        <?php } ?>
      </div>
      <?php if (is_user_logged_in() && !$show_edits) { ?>
        <a href="/details?<?php echo http_build_query(array('record' => $record['tech.id'], 'edits_on' => '1')) ?>">
          <button id="edit-mode" class="btn btn-primary btn-edit">Edit Mode ✏️</button>
        </a>
      <?php } else if (is_user_logged_in()) { ?>
        <a href="/details?<?php echo http_build_query(array('record' => $record['tech.id'], 'edits_on' => '0')) ?>">
          <button id="edit-mode" class="btn btn-primary btn-view">View Mode 👓</button>
        </a>
      <?php } ?>
    </div>

    <div class="record-display">
      <div class="record-info1">

        <div class="img-group">
          <?php if (in_array($record["tech.id"], $seed_data_ids)) { ?>
            <div>
              <small class="text-muted">
                <!-- CITATION: Image was sourced from <?php echo $record['tech.file_source'] ?> -->
                <!-- <cite>
                  Image Source: <a href="<?php echo $record['tech.file_source'] ?>">source</a>
                </cite> -->
              </small>
            </div>
          <?php } ?>
          <img src="<?php echo $media_url ?>" alt="<?php echo $record['tech.name'] ?> image" height=250 width=250>
        </div>

        <div class="record-ratings-tags">

          <div class="tags-group">
            <?php foreach ($tag_records as $tag_record) { ?>
              <div class="badge badge-pill badge-info bg-tag-color tag-mg tag-mg-home ">
                <?php echo htmlspecialchars($tag_record["tag.name"]) ?>
              </div>
            <?php } ?>

            <form class="tag-form" method="get" action="/details/edit">

              <!-- hidden input for record -->
              <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

              <input type="hidden" name="name" value="<?php echo htmlspecialchars($record['tech.name']) ?>">

              <input type="hidden" name="is_tag" value="1">

              <?php if (is_user_logged_in() && $show_edits) { ?>
                <button class="edit-btn tag-edit" type="submit"><i style="font-size:22px" class="fa" id="edit-btn-id">&#xf044;</i></button>
            </form>

          <?php } ?>
          </div>

          <div class="rating-hot-group">
            <p class="rating">
              <span class="rating-logo">
                <?php echo RATING[round($record['review.rating_mean'])] ?>
              </span>
              <?php echo $record['review.rating_mean'] ?> / 5.0
            </p>

            <p class="rating-p rating-caption">The rating for <?php echo $record['tech.name'] ?> is based on it's usability, scalability, and overall effectiveness in the development process</p>

            <?php if ($record['review.hot_count'] > 5) { ?>
              <p class="hot-score"><span class="hot-logo">&#9832</span> <?php echo $reviews_pct_hot_count ?></p>

              <p class="hot-p hot-caption"><?php echo $reviews_pct_hot_count ?> of industry developers and professionals consider this to be a hot and trending technology</p>
            <?php } else { ?>
              <p class="hot-score"><span class="hot-logo">*&#9832</span> <?php echo $reviews_pct_hot_count ?></p>

              <p class="hot-p hot-caption">Currently, only <strong><?php echo $vote_caption ?></strong></p>
              <p class="hot-p hot-caption">This technology does not have enough votes to be considred hot at the moment</p>
            <?php } ?>
          </div>
          <?php if (is_user_logged_in() && $show_edits) { ?>
            <div>
              <form class="tag-form" method="get" action="/details/edit">

                <!-- hidden input for record so we can return back to page -->
                <input type="hidden" name="tech_id" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

                <!-- hidden input for record -->
                <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['review.id']) ?>">

                <!-- hidden input for tech name  -->
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($record['tech.name']) ?>">

                <!-- hidden input for what is being edited -->
                <input type="hidden" name="is_vote" value="1">

                <button class="edit-btn definition-edit" type="submit">Vote</button>
              </form>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="record-info2">
        <div class="definition-group">
          <div class="alert alert-primary def-blurb">
            <h3>
              <?php echo $record['tech.definition'] ?>
            </h3>
            <?php if (is_user_logged_in() && $show_edits) { ?>
              <form method="get" action="/details/edit">

                <!-- hidden input for what is being edited -->
                <input type="hidden" name="to_edit" value="techs.definition">

                <!-- hidden input for record -->
                <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

                <button class="edit-btn definition-edit def-edit-mg"><i style="font-size:22px" class="fa" id="edit-btn-id">&#xf044;</i></button>
              </form>
            <?php } ?>
          </div>
          <div>
            <?php if (in_array($record["tech.id"], $seed_data_ids)) { ?>
              <div class="definition-citation">
                <small class="text-muted">
                  <cite>
                    <!-- CITATION: Image was sourced from <?php echo $record['tech.resource_url'] ?> -->
                    <!-- Information Source: <a href="<?php echo $record['tech.resource_url'] ?>"><?php echo $record['tech.resource_name'] ?></a> -->
                  </cite>
                </small>
              </div>
            <?php } ?>
          </div>
        </div>


        <div class="example-group">
          <div class="h3-edit-group">
            <h3>Example</h3>
            <!-- CITATION: EXAMPLE CREATED BY ME, ASAD NABI-->
            <?php if (is_user_logged_in() && $show_edits) { ?>
              <form method="get" action="/details/edit">

                <!-- hidden input for what is being edited -->
                <input type="hidden" name="to_edit" value="techs.example">

                <!-- hidden input for record -->
                <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

                <button class="edit-btn definition-edit"><i style="font-size:22px" class="fa" id="edit-btn-id">&#xf044;</i></button>

              </form>
            <?php } ?>
          </div>
          <div class="bg-code rounded">
            <pre><code><?php echo htmlspecialchars($record['tech.example']) ?></pre></code>
          </div>
          <?php if (in_array($record["tech.id"], $seed_data_ids)) { ?>
            <div class="example-citation">
              <small class="text-muted">
                <cite>
                  <!-- CITATION: Image was sourced from <?php echo $record['tech.resource_url'] ?> -->
                  <!-- Information Source: <a href="<?php echo $record['tech.resource_url'] ?>"><?php echo $record['tech.resource_name'] ?></a> -->
                </cite>
              </small>
            </div>
          <?php } ?>
        </div>

        <div class="description-group">
          <div class="h3-edit-group">
            <h3>Description</h3>

            <?php if (is_user_logged_in() && $show_edits) { ?>

              <form method="get" action="/details/edit">

                <!-- hidden input for what is being edited -->
                <input type="hidden" name="to_edit" value="techs.description">

                <!-- hidden input for record -->
                <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

                <button class="edit-btn definition-edit"><i style="font-size:22px" class="fa" id="edit-btn-id">&#xf044;</i></button>
              </form>
            <?php } ?>
          </div>
          <p><?php echo $record['tech.description'] ?></p>
          <?php if (in_array($record["tech.id"], $seed_data_ids)) { ?>
            <div class="description-citation">
              <small class="text-muted">
                <cite>
                  <!-- CITATION: Image was sourced from <?php echo $record['tech.resource_url'] ?> -->
                  <!-- Information Source: <a href="<?php echo $record['tech.resource_url'] ?>"><?php echo $record['tech.resource_name'] ?></a> -->
                </cite>
              </small>
            </div>
          <?php } ?>
        </div>

        <div class="description-group">
          <div class="h3-edit-group">
            <h3>Resources</h3>
            <?php if (is_user_logged_in() && $show_edits) { ?>

              <form method="get" action="/details/edit">

                <!-- hidden input for what is being edited -->
                <input type="hidden" name="to_edit" value="techs.resource_name">

                <!-- hidden input for what is being edited -->
                <input type="hidden" name="to_edit2" value="techs.resource_url">

                <!-- hidden input for record -->
                <input type="hidden" name="record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

                <button type="submit" class="edit-btn definition-edit"><i style="font-size:22px" class="fa" id="edit-btn-id">&#xf044;</i></button>
              </form>
            <?php } ?>
          </div>
          <p>
            For further information about <?php echo $record["tech.name"] ?> and to learn more about the technical aspects of it, please check out
            <a href="<?php echo $record['tech.resource_url'] ?>" class="resource-link-show"><?php echo $record['tech.resource_name'] ?></a>
          </p>
        </div>
      </div>
    </div>
    <div class="delete-div">
      <?php if (is_user_logged_in() && $show_edits) { ?>

        <form method="get" action="/details?<?php echo http_build_query(array('record' => $record['tech.id'])) ?>">

          <!-- hidden input for record -->
          <input type="hidden" name="delete_record" value="<?php echo htmlspecialchars($record['tech.id']) ?>">

          <!-- hidden input for media -->
          <input type="hidden" name="delete_media" value="<?php echo htmlspecialchars($plain_url) ?>">

          <!-- hidden input for review -->
          <input type="hidden" name="delete_review_record" value="<?php echo htmlspecialchars($record['review.id']) ?>">

          <button class="delete-btn definition-edit edit-btn btn btn-danger" type="submit" name="delete_entry"><span>Delete Technology</span></button>

        </form>
    </div>
  <?php } ?>

  </main>

  <?php include 'includes/footer.php'; ?>

  <script src="/public/scripts/jquery-3.6.1.js"></script>
  <script src="/public/scripts/edit-btn.js"></script>

</body>

</html>
