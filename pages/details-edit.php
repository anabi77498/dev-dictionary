<?php

$to_edit = $_GET['to_edit'];

$is_tag = $_GET['is_tag'];

$get_id = $_GET['record'];

$sql_select_query = "SELECT techs.id AS 'tech.id', " . $to_edit . " AS 'edit.item' FROM techs WHERE techs.id = :techsId;";

// "SELECT techs.id AS 'tech.id', techs.name AS 'edit.item' FROM techs WHERE techs.id = :techsId;"
$sql_select_query;

if ($is_tag == NULL) {
  $result = exec_sql_query(
    $db,
    $sql_select_query,
    array(
      ':techsId' => $get_id
    )
  );
} else {
  // select from tech-tags
}

$records = $result->fetchAll();

if (count($records) > 0) {
  $record = $records[0]; // first record
}
$curr_value = $record['edit.item'];

$return_url = "/details/edit?" . http_build_query(array('to_edit' => $to_edit, 'record' => $get_id));



// update new record into database ONLY FOR TECHS
if (isset($_POST['make-edit'])) {

  $update_id = $_POST['record'];
  $update_field = $_POST['sql-field'];

  $new_value = $_POST['new_edit'];

  $edit_sql_query = "UPDATE techs SET " . $update_field . " = :edit WHERE (id = :id);";

  $edit_result = exec_sql_query($db, $edit_sql_query, array(
    ':edit' => $new_value,
    ':id' => $update_id
  ));

  $prev_url = "/details?" . http_build_query(array('record' => $update_id));

  $header_val = 'Location: ' . $prev_url;

  if ($edit_result) {
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <title>Developer Dictionary</title>

</head>

<body>
  <?php include 'includes/header.php'; ?>

  <main>

    <?php if ($record == NULL) { ?>

      <h2 class="alert alert-warning" role="alert">
        Cannot retrieve the selected technology
      </h2>
      <p>Please contact us at <em><strong>an448@cornell.edu</strong></em> for assistance or try again at a later time</p>

      <p>Please return to the <strong><a href="/">Home Page</a></strong></p>

    <?php } else { ?>

      <?php if ($to_edit == "techs.name") { ?>

        <h2>Edit the technology name</h2>

        <form action="<?php echo $return_url ?>" method="post" novalidate>

          <input type="hidden" name="record" value="<?php echo $get_id; ?>">

          <input type="hidden" name="sql-field" value="name">
          <div>
            <label for="tech-name">Technology Name: </label>
            <input id='tech-name' type="text" name="new_edit" value="<?php echo $curr_value ?>">
          </div>


          <button type="submit" name="make-edit">will it return back</button>

        </form>

      <?php } ?> <?php } ?>


  </main>

  <?php include 'includes/footer.php'; ?>
</body>

</html>
