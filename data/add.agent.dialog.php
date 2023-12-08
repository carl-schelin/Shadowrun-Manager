<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="agt_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="agt_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability <input type="text" name="agt_avail" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Permission <input type="text" name="agt_perm" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="agt_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="agt_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="agt_page" size="3"></td>
</tr>
</table>
