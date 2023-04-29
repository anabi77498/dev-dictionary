<?php
// 1 MB = 2000000 bytes
define("MAX_FILE_SIZE", 1000000);

$upload_feedback = array(
  'misc_error' => False,
  'too_big' => False
);

// upload fields
$file_source = NULL;
$file_file_ext = NULL;

//form-values
$tech_values = array(
  'name' => '',
  'definition' => '',
  'example' => '',
  'description' => '',
  'resource' => '',
  'resource-url' => '',
  'media-ext' => '',
  'media-source' => ''
);

$review_values = array(
  'rating-mean' => '',
  'rating-count' => 1,
  'hot-yes-count' => '',
  'hot-pct' => '',
  'hot-count' => 1
);

//input => number
//no-input => null
$tag_values = array();

if (isset($_POST["upload"]) && is_user_logged_in()) {

  if ($_POST['tag-prog-lang'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-prog-lang']);
  }

  if ($_POST['tag-web-dev'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-web-dev']);
  }

  if ($_POST['tag-app-dev'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-app-dev']);
  }

  if ($_POST['tag-ds-ml-ai'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-ds-ml-ai']);
  }

  if ($_POST['tag-game-dev'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-game-dev']);
  }

  if ($_POST['tag-os'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-os']);
  }

  if ($_POST['tag-testing'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-testing']);
  }

  if ($_POST['tag-dev-tools'] != NULL) {
    array_push($tag_values, (int) $_POST['tag-dev-tools']);
  }

  $tech_values['name'] = trim($_POST['tech_name']);
  $tech_values['definition'] = trim($_POST['tech_definition']);
  $tech_values['example'] = $_POST['tech_example'];
  $tech_values['description'] = trim($_POST['tech_description']);
  $tech_values['resource'] = trim($_POST['tech_resource']);
  $tech_values['resource-url'] = trim($_POST['tech_resource_url']);
  $review_values['rating-mean'] = (float) $_POST['tech_rating_mean'];
  $review_values['hot-yes-count'] = (int) $_POST['tech_hot'];
  $review_values['hot-pct'] = (float) ((int) $_POST['tech_hot']) / ((int) $review_values['hot-count']);

  //media items
  $file_source = trim($_POST['file_source']);
  if (empty($file_source)) {
    $file_source = NULL;
  }

  // get the info about the uploaded files.
  $file = $_FILES['jpg-png-file'];

  // Assume the form is valid...
  $media_valid = True;

  // file is required
  if ($file['error'] == UPLOAD_ERR_OK) {

    // Get the name of the uploaded file without any path
    $base_file_name = basename($file['name']);

    // Get the file extension of the uploaded file and convert to lowercase for consistency in DB
    $file_ext_upload = strtolower(pathinfo($base_file_name, PATHINFO_EXTENSION));


    if (!in_array($file_ext_upload, array('jpg', 'jpeg', 'png'))) {
      $media_valid = False;
      $upload_feedback['misc_error'] = True;
    }
  } else if (($file['error'] == UPLOAD_ERR_INI_SIZE) || ($file['error'] == UPLOAD_ERR_FORM_SIZE)) {
    $media_valid = False;
    $upload_feedback['too_big'] = True;
  } else {
    $media_valid = False;
    $upload_feedback['misc_error'] = True;
  }

  if ($media_valid) {
    $tech_values['media-ext'] = $file_ext_upload;
    $tech_values['media-source'] = $file_source;

    $result_review = exec_sql_query($db, "INSERT INTO reviews (rating_mean,
    rating_count, hot_yes_count, hot_pct, hot_count) VALUES
    (:rating_mean, :rating_count, :hot_yes_count, :hot_pct, :hot_count);", array(
      ':rating_mean' => $review_values['rating-mean'],
      ':rating_count' => $review_values['rating-count'],
      ':hot_yes_count' => $review_values['hot-yes-count'],
      ':hot_pct' => $review_values['hot-pct'],
      ':hot_count' => $review_values['hot-count']
    ));

    $review_id = $db->lastInsertId('id');


    $result_tech = exec_sql_query($db, "INSERT INTO
    techs (name, definition, example, description, file_ext, file_source,
    resource_name, resource_url, review_id) VALUES
    (:tech_name, :tech_definition, :tech_example, :tech_description,
    :tech_file_ext, :tech_file_source, :tech_resource_name,
    :tech_resource_url, :review_id
    );", array(
      ':tech_name' => $tech_values['name'],
      ':tech_definition' => $tech_values['definition'],
      ':tech_example' => $tech_values['example'],
      ':tech_description' => $tech_values['description'],
      ':tech_file_ext' => $tech_values['media-ext'],
      ':tech_file_source' => $tech_values['media-source'],
      ':tech_resource_name' => $tech_values['resource'],
      ':tech_resource_url' => $tech_values['resource-url'],
      ':review_id' => $review_id
    ));

    $tech_id = $db->lastInsertId('id');

    foreach ($tag_values as $tag) {
      $result_tech_tags = exec_sql_query($db, "INSERT INTO
      tech_tags (tech_id, tag_id) VALUES (:tech_id, :tag_id);", array(
        ':tech_id' => $tech_id,
        ':tag_id' => $tag
      ));
    }

    $upload_storage_path = 'public/uploads/techs/' . $tech_id . '.' . $tech_values['media-ext'];

    if (move_uploaded_file($file["tmp_name"], $upload_storage_path) == False) {
      error_log("Failed to permanently store the uploaded file on the file server. Please check that the server folder exists.");
    }
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
    <?php if (is_user_logged_in()) { ?>
      <h1>Add a Technology</h1>
      <p>The developer dictionary is an open source, community implemented page. This technology will be viewable by all those who access this website and may be used for various, undisclosed purposes. It is your responsibility as a moderator to input valid information into the dictionary. Your content is subjected to edits from others who maintain moderator privileges.</p>


      <section>
        <form action="/entry-insert" method="post" enctype="multipart/form-data">

          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">

          <?php if ($upload_feedback['too_big']) { ?>
            <p class="feedback">The uploaded file is too big. Please upload a file that is 2MB or less</p>
          <?php } ?>

          <?php if ($upload_feedback['misc_error']) { ?>
            <p class="feedback">Something went wrong. Please select a PNG or JPEG/JPG file to upload.</p>
          <?php } ?>

          <div>
            <label for="tech-name">Technology Name: </label>
            <input id='tech-name' type="text" name="tech_name">
          </div>

          <div class="text-area">
            <label for="tech-definition">
              Technology Definition: </label>
            <textarea id='tech-definition' rows="3" cols="60" name="tech_definition"></textarea>
          </div>

          <div class="text-area">
            <p>Please format as coherent code. Include tabs, enter, new-line, etc </p>
            <label for="tech-example">
              Technology example (optional): </label>
            <textarea id='tech-example' rows="3" cols="60" name="tech_example"></textarea>
          </div>

          <div class="text-area">
            <label for="tech-description">
              Technology Description: </label>
            <textarea id='tech-description' rows="10" cols="60" name="tech_description"></textarea>
          </div>

          <div>
            <label for="tech-resource">
              Additional Resources: </label>
            <input id='tech-resource' type="text" name="tech_resource">
          </div>

          <div>
            <label for="tech-resource-url">Source URL:</label>
            <input id='tech-resource-url' type="url" name="tech_resource_url" placeholder="Resource URL">
          </div>




          <div>
            <p>This file must be a JPEG/JPG or PNG</p>
            <label for="file-upload">File:</label>
            <input id="file-upload" type="file" name="jpg-png-file" accept="image/png, image/jpeg, image/jpg,.png, .jpg, .jpeg">
          </div>

          <div>
            <label for="file-source">Source URL (optional):</label>
            <input id='file-source' type="url" name="file_source" placeholder="URL">
          </div>

          <div>
            <p>Please input up to a single decimal places</p>
            <label for="tech-rating">
              Technology Rating (0-5): </label>
            <input id='tech-rating' type="number" min="0" max="5" step=".1" name="tech_rating_mean">
          </div>


          <div role="group" ara-labelledby="hot_choice_head">

            <div id="hot_choice_head">
              Is this Technology Hot in it's industry?
            </div>

            <div>
              <div>
                <input id='tech-hot-yes' type="radio" name="tech_hot" value='1'>
                <label for="tech-hot-yes">Yes</label>
              </div>

              <div>
                <input id='tech-hot-no' type="radio" name="tech_hot" value='0'>
                <label for="tech-hot-no">No</label>
              </div>
            </div>
          </div>

          <div>Please select a category for the technology</div>
          <div>
            <div>
              <input type="checkbox" name="tag-prog-lang" id="programming-lang" value='1' />
              <label for="programming-lang">Programming Language</label>
            </div>
            <div>
              <input type="checkbox" name="tag-web-dev" id="web-dev" value='2' />
              <label for="web-dev">Web Development</label>
            </div>
            <div>
              <input type="checkbox" name="tag-app-dev" id="app-dev" value='3' />
              <label for="app-dev">App Development</label>
            </div>
            <div>
              <input type="checkbox" name="tag-ds-ml-ai" id="ds-ml-ai" value='4' />
              <label for="ds-ml-ai">Data Science, ML and AI</label>
            </div>
            <div>
              <input type="checkbox" name="tag-game-dev" id="game-dev" value='5' />
              <label for="game-dev">Game Development</label>
            </div>
            <div>
              <input type="checkbox" name="tag-os" id="os" value='6' />
              <label for="os">Operating Systems</label>
            </div>
            <div>
              <input type="checkbox" name="tag-testing" id="testing" value='7' />
              <label for="testing">Software Testing</label>
            </div>
            <div>
              <input type="checkbox" name="tag-dev-tools" id="dev-tools" value='8' />
              <label for="dev-tools">Development Tools</label>
            </div>
          </div>

          <div>
            <button type="submit" name="upload">Upload</button>
          </div>
        </form>
      </section>

    <?php } else { ?>
      <div class="alert alert-warning" role="alert">
        <p class="non-login-msg">
          You do not have access to this page!</p>
      </div>
      <p class="non-login-msg">If you have valid login credentials, please login and try again else please return to the <strong><a href="/">Home Page</a></strong></p>
    <?php } ?>
  </main>
  <?php include 'includes/footer.php'; ?>
</body>

</html>
