<?php
# Script: add.tradition.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.tradition.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Traditions</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel(1)) {
?>
function delete_tradition( p_script_url ) {
  var question;
  var answer;

  question  = "The Tradition may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Tradition?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.tradition.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_tradition(p_script_url, update) {
  var at_form = document.dialog;
  var at_url;

  at_url  = '?update='   + update;
  at_url += "&id="       + at_form.id.value;

  at_url += "&trad_name="           + encode_URI(at_form.trad_name.value);
  at_url += "&trad_description="    + encode_URI(at_form.trad_description.value);
  at_url += "&trad_combat="         + encode_URI(at_form.trad_combat.value);
  at_url += "&trad_detection="      + encode_URI(at_form.trad_detection.value);
  at_url += "&trad_health="         + encode_URI(at_form.trad_health.value);
  at_url += "&trad_illusion="       + encode_URI(at_form.trad_illusion.value);
  at_url += "&trad_manipulation="   + encode_URI(at_form.trad_manipulation.value);
  at_url += "&trad_drainleft="      + encode_URI(at_form.trad_drainleft.value);
  at_url += "&trad_drainright="     + encode_URI(at_form.trad_drainright.value);
  at_url += "&trad_book="           + encode_URI(at_form.trad_book.value);
  at_url += "&trad_page="           + encode_URI(at_form.trad_page.value);

  script = document.createElement('script');
  script.src = p_script_url + at_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.tradition.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickTradition' ).click(function() {
    $( "#dialogTradition" ).dialog('open');
  });

  $( "#dialogTradition" ).dialog({
    autoOpen: false,

    modal: true,
    height: 220,
    width:  740,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogTradition" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_tradition('add.tradition.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Tradition",
        click: function() {
          attach_tradition('add.tradition.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Tradition",
        click: function() {
          attach_tradition('add.tradition.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="tradition">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Tradition Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('tradition-help');">Help</a></th>
</tr>
</table>

<div id="tradition-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Language Form</strong>
  <ul>
    <li><strong>Name</strong> - The name of the Metatype.</li>
    <li><strong>Walk</strong> - The Metatype walking speed.</li>
    <li><strong>Run</strong> - The Metatype run speed.</li>
    <li><strong>Swim</strong> - The Metatype swim speed.</li>
    <li><strong>Book</strong> - Select the book where this table is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickTradition" value="Add Tradition"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Traditions...')?></span>

</div>


<div id="dialogTradition" title="Tradition">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="4">Tradition Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Tradition <input type="text" name="trad_name" size="20"></td>
  <td class="ui-widget-content" colspan="3">Description <input type="text" name="trad_description" size="60"></td>
</tr>
<tr>
  <td class="ui-widget-content">Combat <select name="trad_combat">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Detection <select name="trad_detection">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Health <select name="trad_health">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Illusion <select name="trad_illusion">
<?php
  $q_string  = "select s_trad_id,s_trad_name ";
  $q_string .= "from s_tradition ";
  $q_string .= "order by s_trad_name ";
  $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
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
  while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
    print "<option value=\"" . $a_s_tradition['s_trad_id'] . "\">" . $a_s_tradition['s_trad_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="2">Drain <select name="trad_drainleft">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_name ";
  $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysql_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select> + <select name="trad_drainright">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_name ";
  $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysql_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Book  <select name="trad_book">
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
</select>: <input type="text" name="trad_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
