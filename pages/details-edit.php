<?php

$is_home = False;
$is_about = False;

$to_edit = $_GET['to_edit'];

$to_edit2 = $_GET['to_edit2'];

$is_tag = $_GET['is_tag'];

$get_id = $_GET['record'];

$is_vote = $_GET['is_vote'];

$get_id_for_vote = $_GET['tech_id'];

$sql_select_query = '';

$prev_url = "/details?" . http_build_query(array('record' => $get_id));

if ($is_tag == NULL && $to_edit2 == NULL && $is_vote == NULL) {

  $sql_select_query = "SELECT techs.id AS 'tech.id', " . $to_edit . " AS 'edit.item' FROM techs WHERE techs.id = :techsId;";

  $result = exec_sql_query(
    $db,
    $sql_select_query,
    array(
      ':techsId' => $get_id
    )
  );

  $records = $result->fetchAll();

  if (count($records) > 0) {
    $record = $records[0]; // first record
  }

  $curr_value = $record['edit.item'];

  $return_url = "/details/edit?" . http_build_query(array('to_edit' => $to_edit, 'record' => $get_id));
} else if ($is_tag == NULL && $is_vote == NULL && $to_edit2 != NULL) {
  // pull resource and the url of the resource
  $sql_select_query = "SELECT techs.id AS 'tech.id', " . $to_edit . " AS 'edit.item.name', " . $to_edit2 . " AS 'edit.item.url' FROM techs WHERE techs.id = :techsId";

  $result = exec_sql_query(
    $db,
    $sql_select_query,
    array(
      ':techsId' => $get_id
    )
  );

  $records = $result->fetchAll();

  if (count($records) > 0) {
    $record = $records[0]; // first record
  }

  $curr_value_name = $record['edit.item.name'];
  $curr_value_url = $record['edit.item.url'];

  $return_url = "/details/edit?" . http_build_query(array('to_edit' => $to_edit, 'to_edit2' => $to_edit2, 'record' => $get_id));

  $prev_url = "/details?" . http_build_query(array('record' => $get_id));
};


// update new record into database ONLY FOR TECHS
if (isset($_POST['make-edit'])) {

  if (isset($_POST['sql-field'])) {
    $update_id = $_POST['record'];
    $update_field = $_POST['sql-field'];

    $new_value = $_POST['new_edit'];

    $edit_sql_query = "UPDATE techs SET " . $update_field . " = :edit WHERE (id = :id);";

    $edit_result = exec_sql_query($db, $edit_sql_query, array(
      ':edit' => $new_value,
      ':id' => $update_id
    ));

    $prev_url = "/details?" . http_build_query(array('record' => $update_id));
  }
  if (isset($_POST['sql-field-1']) && isset($_POST['sql-field-2'])) {
    $update_id = $_POST['record'];
    $update_field_1 = $_POST['sql-field-1'];
    $update_field_2 = $_POST['sql-field-2'];

    $new_value_1 = $_POST['new_edit_1'];
    $new_value_2 = $_POST['new_edit_2'];

    $edit_sql_query = "UPDATE techs SET " . $update_field_1 . " = :edit_1, " . $update_field_2 . " = :edit_2" .  " WHERE (id = :id);";

    // "UPDATE techs SET resource_name = :edit_1, resource_url = :edit_2 WHERE (id = :id);"

    $edit_result = exec_sql_query($db, $edit_sql_query, array(
      ':edit_1' => $new_value_1,
      ':edit_2' => $new_value_2,
      ':id' => $update_id
    ));

    $prev_url = "/details?" . http_build_query(array('record' => $update_id));
  }

  $header_val = 'Location: ' . $prev_url;

  if ($edit_result) {
    header($header_val);
  };
}

if (isset($_POST['add-tag'])) {
  //insert into tech-tag db
  $tech_id = $_POST['tech_id'];
  $tag_id = $_POST['tag_id'];

  $add_tag = exec_sql_query($db, "INSERT INTO
    tech_tags (tech_id, tag_id) VALUES (:tech_id, :tag_id);", array(
    ':tech_id' => $tech_id,
    ':tag_id' => $tag_id
  ));
};

if (isset($_POST['delete-tag'])) {
  $id_del = $_POST['record'];

  $delete_tag = exec_sql_query($db, "DELETE FROM tech_tags WHERE id = :tech_tag_id;", array(
    ':tech_tag_id' => $id_del
  ));
}


//display tag AFTER changes
if (isset($_GET['is_tag'])) {

  $tech_name = $_GET['name'];

  $sql_select_query = "SELECT tech_tags.id AS 'tech_tag.id',
  tech_tags.tech_id AS 'tech_tag.tech_id',
  tech_tags.tag_id AS 'tech_tag.tag_id',
  tags.name AS 'tag.name'
  FROM tech_tags
  INNER JOIN tags ON tech_tags.tag_id = tags.id
  WHERE tech_tags.tech_id = :techsId;";

  $result = exec_sql_query(
    $db,
    $sql_select_query,
    array(
      ':techsId' => $get_id
    )
  );

  $records = $result->fetchAll();

  $return_url = "/details/edit?" . http_build_query(array('record' => $get_id, 'name' => $tech_name, 'is_tag' => $is_tag));

  $curr_tags = array();
  foreach ($records as $tag_record) {
    array_push($curr_tags, $tag_record["tech_tag.tag_id"]);
  };

  $tag_results = exec_sql_query($db, "SELECT * FROM tags;");

  $tags = $tag_results->fetchAll();
}

//votes
$received_vote = False;

if (isset($_GET['is_vote'])) {
  $review_id = $_GET['record'];
  $tech_name = $_GET['name'];

  $return_url = "/details/edit?" . http_build_query(array('record' => $review_id, 'is_vote' => 1));
}

if (isset($_POST['make-vote'])) {
  $review_id = $_POST['record'];
  $tech_id = $_POST['tech_id'];

  $sql_select_query = 'SELECT * FROM reviews WHERE id = :review_id;';

  $result = exec_sql_query($db, $sql_select_query, array(':review_id' => $review_id));

  $records = $result->fetchAll();

  if (count($records) > 0) {
    $record = $records[0]; // first record
  }

  $new_tech_rating = (float) $_POST['new_tech_rating']; //4.5
  $is_hot_vote = (bool) $_POST['new_tech_hot'];

  $sum_val = (((float) $record['rating_mean']) * ((float) $record['rating_count'])) + $new_tech_rating;

  $insert_count = (int) $record['rating_count'] + 1;

  $tech_rating = fdiv($sum_val, (float) $insert_count);

  $insert_rating_mean = round($tech_rating, 1);

  $insert_hot_count = (int) $record['hot_count'] + 1;
  $insert_hot_yes_count = (int) $record['hot_yes_count'];
  if ($is_hot_vote) {
    //update
    $insert_hot_yes_count = (int) $record['hot_yes_count'] + 1;
  }
  $insert_hot_pct = round(($insert_hot_yes_count / $insert_hot_count), 2);

  // "UPDATE techs SET resource_name = :edit_1, resource_url = :edit_2 WHERE (id = :id);"

  $add_vote = exec_sql_query($db, "UPDATE reviews SET rating_mean = :new_rating_mean, rating_count = :new_rating_count, hot_yes_count = :new_hot_yes_count, hot_pct = :new_hot_pct, hot_count = :new_hot_count WHERE (id = :id);", array(
    ':new_rating_mean' => $insert_rating_mean,
    ':new_rating_count' => $insert_count,
    ':new_hot_yes_count' => $insert_hot_yes_count,
    ':new_hot_pct' => $insert_hot_pct,
    ':new_hot_count' => $insert_hot_count,
    ':id' => $review_id
  ));

  $prev_url = "/details?" . http_build_query(array('record' => $tech_id));

  if ($add_vote) {
    $received_vote = True;
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

  <main class="details-entry">

    <?php if ($record == NULL && $records == NULL && $is_vote == NULL) { ?>

      <h2 class="alert alert-warning" role="alert">
        Cannot retrieve the selected technology
      </h2>
      <p>Please contact us at <em><strong>an448@cornell.edu</strong></em> for assistance or try again at a later time</p>

      <p>Please return to the <strong><a href="/">Home Page</a></strong></p>

    <?php } else if ($is_tag == NULL && $is_vote == NULL) { ?>

      <?php if ($to_edit == "techs.name") { ?>

        <h2>Edit the technology's name</h2>

        <form class="form-name" action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $get_id; ?>">

          <input type="hidden" name="sql-field" value="name">

          <div>

            <label for="tech-name" class="head-label">Technology Name: </label>

            <input id='tech-name' class="text form-control" type="text" name="new_edit" value="<?php echo $curr_value ?>">

          </div>
          <div class="btn-submit">
            <button type="submit" name="make-edit">Edit</button>
          </div>
        </form>
        <a href="<?php echo $prev_url ?>"><button class="return-btn-tags">Return</button></a>
      <?php } ?>

      <?php if ($to_edit == "techs.definition") { ?>

        <h2>Edit the technology's definition</h2>

        <form class="form-definition" action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $get_id; ?>">

          <input type="hidden" name="sql-field" value="definition">

          <div class="text-area textarea-input-edit">
            <label for="tech-definition" class="head-label">Technology Definition: </label>

            <textarea id='tech-definition' class="form-control" rows="3" cols="60" name="new_edit"><?php echo $curr_value ?></textarea>
          </div>

          <div class="btn-submit">
            <button type="submit" name="make-edit">Edit</button>
          </div>
        </form>
        <a href="<?php echo $prev_url ?>"><button class="return-btn-tags">Return</button></a>
      <?php } ?>

      <?php if ($to_edit == "techs.example") { ?>

        <h2>Edit the technology's example</h2>

        <form class="form-example" action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $get_id; ?>">

          <input type="hidden" name="sql-field" value="example">

          <div class="text-area textarea-input-edit">
            <label for="tech-example" class="head-label">Technology Example: </label>

            <textarea id='tech-example' rows="3" cols="60" name="new_edit" class="form-control"><?php echo $curr_value ?></textarea>
          </div>

          <div class="btn-submit">
            <button type="submit" name="make-edit">Edit</button>
          </div>
        </form>
        <a href="<?php echo $prev_url ?>"><button class="return-btn-tags">Return</button></a>
      <?php } ?>

      <?php if ($to_edit == "techs.description") { ?>

        <h2>Edit the technology's description</h2>

        <form class="form-description" action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $get_id; ?>">

          <input type="hidden" name="sql-field" value="description">

          <div class="text-area textarea-input-edit">
            <label for="tech-description" class="head-label">Technology Description: </label>

            <textarea id='tech-description' rows="10" cols="60" name="new_edit" class="form-control"><?php echo $curr_value ?></textarea>
          </div>

          <div class="btn-submit">
            <button type="submit" name="make-edit">Edit</button>
          </div>
        </form>
        <a href="<?php echo $prev_url ?>"><button class="return-btn-tags">Return</button></a>
      <?php } ?>

      <?php if ($to_edit == "techs.resource_name" && $to_edit2 == "techs.resource_url") { ?>

        <h2>Edit the technology's resource</h2>

        <form class="form-resource" action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $get_id; ?>">

          <input type="hidden" name="sql-field-1" value="resource_name">

          <input type="hidden" name="sql-field-2" value="resource_url">

          <div>

            <label for="tech-resource-name" class="head-label">Technology Resource: </label>

            <input id='tech-resource-name' type="text" class="form-control text" name="new_edit_1" value="<?php echo $curr_value_name ?>">

          </div>

          <div class="url-section">

            <label for="tech-resource-url" class="head-label">Technology Url: </label>

            <input id='tech-resource-url' type="url" class="form-control text" name="new_edit_2" value="<?php echo $curr_value_url ?>">
          </div>

          <div class="btn-submit">
            <button type="submit" name="make-edit">Edit</button>
          </div>

        </form>
        <a href="<?php echo $prev_url ?>"><button class="return-btn-tags">Return</button></a>
      <?php } ?>

    <?php } else if ($is_tag == NULL && $is_vote != NULL) { ?>

      <?php if ($received_vote) { ?>
        <div class="alert alert-success" role="alert">
          <p class="success-login-msg">We have successfully received your vote!</p>
        </div>
        <p>Please return to the <a href="<?php echo $prev_url ?>">previous page</a></p>
      <?php } else { ?>
        <h2>Leave a rating for <?php echo $tech_name ?></h2>
        <form action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $review_id; ?>">

          <input type="hidden" name="tech_id" value="<?php echo $get_id_for_vote; ?>">

          <div>
            <p>Please input values up to a single decimal places</p>
            <label for="tech-rating">
              Technology Rating (0-5): </label>
            <input id='tech-rating' type="number" min="0" max="5" step=".1" name="new_tech_rating">
          </div>


          <div role="group" ara-labelledby="hot_choice_head">

            <div id="hot_choice_head">
              Is this Technology Hot in it's industry?
            </div>

            <div>
              <div>
                <input id='tech-hot-yes' type="radio" name="new_tech_hot" value='1'>
                <label for="tech-hot-yes">Yes</label>
              </div>

              <div>
                <input id='tech-hot-no' type="radio" name="new_tech_hot" value='0'>
                <label for="tech-hot-no">No</label>
              </div>
            </div>
          </div>
          </div>
          <button type="submit" name="make-vote">Vote</button>
        </form>
      <?php } ?>
    <?php } else { ?>

      <div class="tag-edit-body">

        <div class="add-tag">
          <h3 class="add-tag-title">Add a new tag to <?php echo $tech_name ?></h3>

          <div class="filter-form">
            <form class="filter-form" action="<?php echo $return_url ?>" method="post">
              <label for="filter-tag">Tags: </label>
              <select id="filter-tag" name="tag_id" required>
                <option value='' disabled selected>Select Tag</option>

                <?php foreach ($tags as $tag) { ?>
                  <?php if (!in_array($tag['id'], $curr_tags)) { ?>
                    <option value='<?php echo $tag['id']; ?>'>
                      <?php echo $tag['name']; ?>
                    </option>
                  <?php } ?>
                <?php } ?>
              </select>

              <input type="hidden" name="tech_id" value="<?php echo $get_id; ?>">
              <div class="filter-submit-btn">
                <button type="submit" name="add-tag">Add Tag</button>
              </div>
            </form>
          </div>
        </div>

        <div class="del-tag">
          <h3 class="curr-tags-title">Current tags of <?php echo $tech_name ?></h3>
          <div class="tags-group-edit">
            <?php foreach ($records as $tag_record) { ?>
              <div class="tag-x-group">
                <div class="badge badge-pill badge-info bg-tag-color tag-mg tag-mg-home tag-edits">
                  <p><?php echo htmlspecialchars($tag_record["tag.name"]) ?></p>
                </div>

                <form action="<?php echo $return_url ?>" method="post" novalidate>
                  <!-- hidden input for tech_tag id -->
                  <input type="hidden" name="record" value="<?php echo htmlspecialchars($tag_record['tech_tag.id']) ?>">

                  <button type="submit" name="delete-tag" class="tag-x">❌</button>
                </form>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <a href="<?php echo $prev_url ?>"><button class="return-btn-tags">Return</button></a>

    <?php } ?>


  </main>

  <?php include 'includes/footer.php'; ?>
</body>

</html>
