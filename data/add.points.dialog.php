<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Life Points <input type="text" name="point_number" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Costs <input type="text" name="point_cost" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Lifestyle Level <input type="text" name="point_level" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="point_book">
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
</select>: <input type="text" name="point_page" size="3"></td>
</tr>
</table>
