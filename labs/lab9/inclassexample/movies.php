<?php
  
  include('includes/init.inc.php');      // DOCTYPE and opening tags
  include('includes/config.inc.php');
  include('includes/functions.inc.php'); // shared functions
?>
<title>PHP &amp; MySQL - ITWS</title>

<?php include('includes/head.inc.php'); ?>

<h1>PHP &amp; MySQL</h1>

<?php include('includes/menubody.inc.php'); ?>

<?php
// ------------------ DATABASE CONNECTION ------------------
$dbOk = false;

/* Create a new database connection object, passing in the host, username,
   password, and database to use. The "@" suppresses errors. */
@$db = new mysqli(
   $GLOBALS['DB_HOST'],
   $GLOBALS['DB_USERNAME'],
   $GLOBALS['DB_PASSWORD'],
   $GLOBALS['DB_NAME']
);

if ($db->connect_error) {
   echo '<div class="messages">Could not connect to the database. Error: ';
   echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
} else {
   $dbOk = true;
}

// ------------------ HANDLE ADD MOVIE FORM ------------------
$havePost = isset($_POST["save"]);

$errors = '';
$title = '';
$year  = '';

if ($havePost) {

   // Clean for on-screen output
   $title = htmlspecialchars(trim($_POST["title"] ?? ''));
   $year  = htmlspecialchars(trim($_POST["year"] ?? ''));

   $focusId = '';

   // Title is required
   if ($title == '') {
      $errors .= '<li>Title may not be blank</li>';
      if ($focusId == '') $focusId = '#title';
   }

   // Year optional, but if present must be 4 digits
   if ($year != '' && !preg_match('/^[0-9]{4}$/', $year)) {
      $errors .= '<li>Year must be 4 digits (yyyy) or left blank</li>';
      if ($focusId == '') $focusId = '#year';
   }

   if ($errors != '') {
      echo '<div class="messages"><h4>Please correct the following errors:</h4><ul>';
      echo $errors;
      echo '</ul></div>';
      echo '<script type="text/javascript">';
      echo '  $(document).ready(function() {';
      echo '    $("' . $focusId . '").focus();';
      echo '  });';
      echo '</script>';
   } else if ($dbOk) {

      $titleForDb = trim($_POST["title"]);
      $yearForDb  = trim($_POST["year"]);

      $insQuery = "INSERT INTO movies (`title`,`year`) VALUES(?, ?)";
      $statement = $db->prepare($insQuery);
      $statement->bind_param("ss", $titleForDb, $yearForDb);
      $statement->execute();

      echo '<div class="messages"><h4>Success: ' . $statement->affected_rows . ' movie added to database.</h4>';
      echo $title . ' (' . $year . ')</div>';

      $statement->close();

      // clear form after insert
      $title = '';
      $year  = '';
   }
}
?>

<h3>Add Movie</h3>
<form id="addMovieForm" name="addMovieForm" action="movies.php" method="post">
   <fieldset>
      <div class="formData">

         <label class="field" for="title">Title:</label>
         <div class="value">
            <input type="text" size="60"
                   value="<?php if ($havePost && $errors != '') echo $title; ?>"
                   name="title" id="title" />
         </div>

         <label class="field" for="year">Year:</label>
         <div class="value">
            <input type="text" size="10" maxlength="4"
                   value="<?php if ($havePost && $errors != '') echo $year; ?>"
                   name="year" id="year" />
            <em>yyyy</em>
         </div>

         <input type="submit" value="save" id="save" name="save" />
      </div>
   </fieldset>
</form>

<h3>Movies</h3>
<table id="movieTable">
<?php
   if ($dbOk) {

      $query = 'SELECT * FROM movies ORDER BY title';
      $result = $db->query($query);
      $numRecords = $result->num_rows;

      echo '<tr><th>Title:</th><th>Year:</th><th></th></tr>';
      for ($i = 0; $i < $numRecords; $i++) {
         $record = $result->fetch_assoc();
         if ($i % 2 == 0) {
            echo "\n" . '<tr id="movie-' . $record['movieid'] . '"><td>';
         } else {
            echo "\n" . '<tr class="odd" id="movie-' . $record['movieid'] . '"><td>';
         }
         echo htmlspecialchars($record['title']);
         echo '</td><td>';
         echo htmlspecialchars($record['year']);
         echo '</td><td>';
         echo '<img src="resources/delete.png" class="deleteMovie" width="16" height="16" alt="delete movie" />';
         echo '</td></tr>';
      }

      $result->free();
      $db->close();
   }
?>
</table>
<h3>Movies and Their Actors</h3>
<table id="movieActorTable">
<?php
   // Open a new connection (simplest way)
   @$db2 = new mysqli(
      $GLOBALS['DB_HOST'],
      $GLOBALS['DB_USERNAME'],
      $GLOBALS['DB_PASSWORD'],
      $GLOBALS['DB_NAME']
   );

   if ($db2->connect_error) {
      echo '<tr><td colspan="3">Could not connect to database for movie/actor list.</td></tr>';
   } else {

      $query = "
         SELECT m.title, m.year, a.first_names, a.last_name
         FROM movies m
         JOIN movie_actors ma ON ma.movieid = m.movieid
         JOIN actors a        ON a.actorid = ma.actorid
         ORDER BY m.title, a.last_name, a.first_names
      ";

      $result = $db2->query($query);

      echo '<tr><th>Movie</th><th>Year</th><th>Actor</th></tr>';

      while ($row = $result->fetch_assoc()) {
         echo '<tr>';
         echo '<td>' . htmlspecialchars($row['title']) . '</td>';
         echo '<td>' . htmlspecialchars($row['year']) . '</td>';
         echo '<td>' . htmlspecialchars($row['first_names'] . ' ' . $row['last_name']) . '</td>';
         echo '</tr>';
      }

      $result->free();
      $db2->close();
   }
?>
</table>



<?php include('includes/foot.inc.php'); ?>
