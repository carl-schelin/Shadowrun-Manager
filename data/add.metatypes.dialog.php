<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="meta_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Walk <input type="text" name="meta_walk" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Run <input type="text" name="meta_run" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Swim <input type="text" name="meta_swim" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Notes <input type="text" name="meta_notes" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="meta_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysql_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="meta_page" size="3"></td>
</tr>
</table>
