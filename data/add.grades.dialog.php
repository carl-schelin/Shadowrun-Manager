<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Bio/Cyberware Grade Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="5">Grade <input type="text" name="grade_name" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Essence Modifier <input type="text" name="grade_essence" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Availability Modifier <input type="text" name="grade_avail" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Cost Modifier <input type="text" name="grade_cost" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="grade_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select> <input type="text" name="grade_page" size="5"></td>
</tr>
</table>
