<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Tradition <input type="text" name="trad_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="trad_description" size="70"></td>
</tr>
<tr>
  <td class="ui-widget-content">Combat <select name="trad_combat">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Detection <select name="trad_detection">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Health <select name="trad_health">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Illusion <select name="trad_illusion">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Manipulation <select name="trad_manipulation">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Drain <select name="trad_drainleft">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_name ";
  $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysqli_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select> + <select name="trad_drainright">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_name ";
  $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysqli_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="trad_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="trad_page" size="3"></td>
</tr>
</table>
