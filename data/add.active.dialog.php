<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Type <input type="text" name="act_type" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="act_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Group <input type="text" name="act_group" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Linked Attribute <select name="act_attribute">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_id ";
  $q_attributes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysqli_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Default? <input type="checkbox" checked="false" name="act_default"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="act_book">
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
</select>: <input type="text" name="act_page" size="5"></td>
</tr>
</table>
