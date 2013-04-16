<?php
/****************************************************************************
 * CODE FILE   : timeregistration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 april 2013
 * Description : Time registration form
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

// application_top //
require('includes/application_top.php');
// Check if user is logged in. If not, redirect to login page
if (!tep_not_null($_SESSION['employee']))
  tep_redirect(tep_href_link(FILENAME_LOGIN));
// Check if the user is allowed to view this page
if (!$_SESSION['employee']->profile->right['timeregistration'])
  tep_redirect(tep_href_link(FILENAME_DEFAULT));

// Make a connection to the database...
$database = new database();
$database->connect(DB_TICKET_SERVER_NAME, DB_TICKET_SERVER_USERNAME, DB_TICKET_SERVER_PASSWORD, DB_TICKET_DATABASE_NAME) or die('Unable to connect to database server!');

$ticket_sql = str_replace('%TICKET_DATE%', $_REQUEST['activityDate'], DB_TICKET_DATABASE_QUERY);

// header //
require(DIR_WS_INCLUDES . 'header_dialog.php'); ?>
<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
  <tr>
    <td class="entryListing-heading"><?php echo TEXT_TIMEREGISTRATION_TICKET_LOOKUP_TICKETNUMBER; ?></td>
    <td class="entryListing-heading"><?php echo TEXT_TIMEREGISTRATION_TICKET_LOOKUP_TICKETDESCRIPTION; ?></td>
  </tr>
  <?php $ticket_query = $database->query($ticket_sql);
  if ($database->num_rows($ticket_query) > 0) {
    $odd_or_even = "odd";
    while ($ticket_result = $database->fetch_array($ticket_query, MYSQL_NUM)) { ?>
      <tr onmouseover="this.className='selectableRowMouseOver-<?php echo $odd_or_even; ?>'" onmouseout="this.className='entryListing-<?php echo $odd_or_even; ?>'" class="entryListing-<?php echo $odd_or_even; ?>" onclick="closeWindow('<?php echo $ticket_result[0]; ?>', '<?php echo rawurlencode($ticket_result[1]); ?>');">
        <td class="entryListing-data"><?php echo $ticket_result[0]; ?></td>
        <td class="entryListing-data"><?php echo $ticket_result[1]; ?></td>
      </tr>
      <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
    }
  } else { ?>
    <tr class="entryListing-odd">
      <td class="entryListing-data" colspan="2" align="center">
        <?php echo TEXT_TIMEREGISTRATION_TICKET_LOOKUP_IS_EMPTY; ?>
      </td>
    </tr>
  <?php } 
  $database->free_result($ticket_query);
  // Close database
  $database->close(); ?>
</table>
<script type="text/javascript">
  var retVal = array();
  window.returnValue = [];

  function closeWindow(ticketNumber, ticketDescription) {
    window.returnValue = [ticketNumber, ticketDescription];
    window.close();
  }
</script>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer_dialog.php'); ?>
<!-- footer_eof //-->
<!-- application_bottom //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!-- application_bottom_eof //-->