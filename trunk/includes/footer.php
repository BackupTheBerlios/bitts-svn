<?php
/****************************************************************************
 * CODE FILE   : footer.php
 * Project     : BitTS - BART it TimeSheet
 * Auteur(s)   : Erwin Beukhof
 * Datum       : 26 november 2007
 * Beschrijving: .....
 *               .....
 */
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="footer">
        <td class="footer">&nbsp;&nbsp;<?php echo tep_strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
        <td align="right" class="footer">&nbsp;&nbsp;<?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
      </tr>
    </table>
    <br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" class="smallText"><?php echo FOOTER_TEXT_BODY; ?></td>
      </tr>
    </table>
    <br>
  </body>
</html>
