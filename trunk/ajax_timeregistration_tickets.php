<?php
if ($_REQUEST['ticket_id']) {
  // Include server parameters
  require('includes/configuration.php');

  // Include the list of application filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

  // Include the database functions
  require(DIR_WS_CLASSES . 'database.php');

  // Make a connection to the database...
  $database = new database();
  $database->connect(DB_TICKET_SERVER_NAME, DB_TICKET_SERVER_USERNAME, DB_TICKET_SERVER_PASSWORD, DB_TICKET_DATABASE_NAME) or die('Unable to connect to database server!');

  $ticket_sql = str_replace('%TICKET_ID%', 0 + $_REQUEST['ticket_id'], DB_TICKET_DATABASE_QUERY);
  $ticket_query = $database->query($ticket_sql);

  if ($ticket_result = $database->fetch_array($ticket_query)) {
    echo $ticket_result['TicketDescription'];
  }

  // Close database
  $database->close();
} else {
  echo '';
}
?>