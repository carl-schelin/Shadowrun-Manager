<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="12">Active Skill Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="4">Type <input type="text" name="act_type" size="20"></td>
  <td class="ui-widget-content" colspan="4">Name <input type="text" name="act_name" size="20"></td>
  <td class="ui-widget-content" colspan="4">Group <input type="text" name="act_group" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Linked Attribute <select name="act_attribute">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_id ";
  $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysql_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="3">Default? <input type="checkbox" checked="false" name="act_default"></td>
  <td class="ui-widget-content" colspan="3">Book <select name="act_book">
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
</select></td>
  <td class="ui-widget-content" colspan="3">Page <input type="text" name="act_page" size="5"></td>
</tr>
</table>
