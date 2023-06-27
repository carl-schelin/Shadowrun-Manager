<?php
# Script: add.adept.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.adept.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Active Skills</title>

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
function delete_adept( p_script_url ) {
  var question;
  var answer;

  question  = "The Adept Power may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Adept Power?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.adept.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_adept(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&adp_name="    + encode_URI(aa_form.adp_name.value);
  aa_url += "&adp_desc="    + encode_URI(aa_form.adp_desc.value);
  aa_url += "&adp_power="   + encode_URI(aa_form.adp_power.value);
  aa_url += "&adp_level="   + encode_URI(aa_form.adp_level.value);
  aa_url += "&adp_book="    + encode_URI(aa_form.adp_book.value);
  aa_url += "&adp_page="    + encode_URI(aa_form.adp_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.adept.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.adept.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickAdeptPower' ).click(function() {
    $( "#dialogAdeptPower" ).dialog('open');
  });

  $( "#dialogAdeptPower" ).dialog({
    autoOpen: false,
    modal: true,
    height: 225,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogAdeptPower" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_adept('add.adept.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Adept Power",
        click: function() {
          attach_adept('add.adept.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Adept Power",
        click: function() {
          attach_adept('add.adept.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

$("#button-update").button("disable");

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="adept">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Adept Powers Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('adept-help');">Help</a></th>
</tr>
</table>

<div id="adept-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Adept Power</strong></p>

<p>As per other sections, check the system and page number for details. Specifically when a power says "by level" in the description, 
it means the power level is as high as the Adept's magic rating. If a Magic rating of 4, then a power with .5 Power Points per Level, 
you can go up to 8 levels potentially giving yourself +8 dice.</p>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addadp" id="clickAdeptPower" value="Add Adept Power"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Adept Powers...')?></span>

</div>


<div id="dialogAdeptPower" title="Adept Powers">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Name <input type="text" name="adp_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Description <input type="text" name="adp_desc" size="60"></td>
</tr>
<tr>
  <td class="ui-widget-content">Level <input type="text" name="adp_level" size="5"> (0 for Limited By Magic)</td>
</tr>
<tr>
  <td class="ui-widget-content">Power Points <input type="text" name="adp_power" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book <select name="adp_book">
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
  <td class="ui-widget-content">Page <input type="text" name="adp_page" size="5"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
