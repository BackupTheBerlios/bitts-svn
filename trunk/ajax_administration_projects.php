<?php
/****************************************************************************
 * CODE FILE   : ajax_administration_projects.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 aug 2011
 * Description : AJAX module for project administration
 */

  // application_top //
  require('includes/application_top.php');
  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));
  // Check if the user is allowed to view this page
  if (!$_SESSION['employee']->profile->right['administration'])
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  // Create a new project object with id == 0 (default)
  $_SESSION['project'] = new project(0, null, $_REQUEST['showhistory']);
?>
              <!-- ********** projectEntries ********** //-->
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr valign="top">
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_NAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_DESCRIPTION; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_CUSTOMERS_CONTACT_NAME.'<br>'.TEXT_PROJECTS_CUSTOMERS_REFERENCE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_START_DATE.'<br>'.TEXT_PROJECTS_END_DATE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_CALCULATED_HOURS.'<br>'.TEXT_PROJECTS_CALCULATED_HOURS_PERIOD; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_BUSINESS_UNITS; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['project']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['project']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->description; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->customers_contact_name.'<br>'.$_SESSION['project']->listing[$index]->customers_reference; ?></td>
                      <td class="entryListing-data"><?php echo tep_strftime(DATE_FORMAT_SHORT, $_SESSION['project']->listing[$index]->start_date).'<br>'.($_SESSION['project']->listing[$index]->end_date!=0?tep_strftime(DATE_FORMAT_SHORT, $_SESSION['project']->listing[$index]->end_date):'&#8734;'); ?></td>
                      <td class="entryListing-data"><?php echo ($_SESSION['project']->listing[$index]->calculated_hours>0?$_SESSION['project']->listing[$index]->calculated_hours.'<br>'.$PROJECTS_CALCULATED_HOURS_PERIOD[$_SESSION['project']->listing[$index]->calculated_hours_period]:BODY_TEXT_NOT_APPLICABLE); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->business_unit->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->customer->name; ?></td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'enter_data', 'projects_id'=>$_SESSION['project']->listing[$index]->id, 'projects_name'=>$_SESSION['project']->listing[$index]->name, 'projects_description'=>$_SESSION['project']->listing[$index]->description, 'projects_customers_contact_name'=>$_SESSION['project']->listing[$index]->customers_contact_name, 'projects_customers_reference'=>$_SESSION['project']->listing[$index]->customers_reference, 'projects_start_date'=>$_SESSION['project']->listing[$index]->start_date, 'projects_end_date'=>$_SESSION['project']->listing[$index]->end_date, 'projects_calculated_hours'=>$_SESSION['project']->listing[$index]->calculated_hours, 'projects_calculated_hours_period'=>$_SESSION['project']->listing[$index]->calculated_hours_period, 'projects_business_units_id'=>$_SESSION['project']->listing[$index]->business_unit->id, 'projects_customers_id'=>$_SESSION['project']->listing[$index]->customer->id), array(), 'hidden_field');
                        echo tep_draw_hidden_field('mPath', $_REQUEST['mPath']);
                        echo tep_draw_hidden_field('show_history', $_REQUEST['showhistory']);
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'delete_entry', 'projects_id'=>$_SESSION['project']->listing[$index]->id, 'projects_name'=>$_SESSION['project']->listing[$index]->name, 'projects_description'=>$_SESSION['project']->listing[$index]->description, 'projects_customers_contact_name'=>$_SESSION['project']->listing[$index]->customers_contact_name, 'projects_customers_reference'=>$_SESSION['project']->listing[$index]->customers_reference, 'projects_start_date'=>$_SESSION['project']->listing[$index]->start_date, 'projects_end_date'=>$_SESSION['project']->listing[$index]->end_date, 'projects_calculated_hours'=>$_SESSION['project']->listing[$index]->calculated_hours, 'projects_calculated_hours_period'=>$_SESSION['project']->listing[$index]->calculated_hours_period, 'projects_business_units_id'=>$_SESSION['project']->listing[$index]->business_unit->id, 'projects_customers_id'=>$_SESSION['project']->listing[$index]->customer->id), array(), 'hidden_field');
                        echo tep_draw_hidden_field('mPath', $_REQUEST['mPath']);
                        echo tep_draw_hidden_field('show_history', $_REQUEST['showhistory']);
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="9"  style="text-align:center">
                      <?php echo TEXT_PROJECTS_LISTING_IS_EMPTY; ?>
                    </td>
                  </tr>
                <?php } ?>
              </table>
              <!-- ********** projectEntries ********** //-->
<?php
  // application_bottom //
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>