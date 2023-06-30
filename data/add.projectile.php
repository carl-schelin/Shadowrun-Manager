<?php
# Script: add.projectile.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.projectile.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Projectile Weapons</title>

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
function delete_projectile( p_script_url ) {
  var question;
  var answer;

  question  = "The Projectile Weapon may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Projectile Weapon?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.projectile.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_projectile(p_script_url, update) {
  var ap_form = document.dialog;
  var ap_url;

  ap_url  = '?update='   + update;
  ap_url += "&id="       + ap_form.id.value;

  ap_url += "&proj_class="      + encode_URI(ap_form.proj_class.value);
  ap_url += "&proj_name="       + encode_URI(ap_form.proj_name.value);
  ap_url += "&proj_rating="     + encode_URI(ap_form.proj_rating.value);
  ap_url += "&proj_acc="        + encode_URI(ap_form.proj_acc.value);
  ap_url += "&proj_damage="     + encode_URI(ap_form.proj_damage.value);
  ap_url += "&proj_type="       + encode_URI(ap_form.proj_type.value);
  ap_url += "&proj_strength="   + ap_form.proj_strength.checked;
  ap_url += "&proj_ap="         + encode_URI(ap_form.proj_ap.value);
  ap_url += "&proj_avail="      + encode_URI(ap_form.proj_avail.value);
  ap_url += "&proj_perm="       + encode_URI(ap_form.proj_perm.value);
  ap_url += "&proj_baseline="   + encode_URI(ap_form.proj_baseline.value);
  ap_url += "&proj_duration="   + encode_URI(ap_form.proj_duration.value);
  ap_url += "&proj_index="      + encode_URI(ap_form.proj_index.value);
  ap_url += "&proj_cost="       + encode_URI(ap_form.proj_cost.value);
  ap_url += "&proj_book="       + encode_URI(ap_form.proj_book.value);
  ap_url += "&proj_page="       + encode_URI(ap_form.proj_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.projectile.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickProjectile' ).click(function() {
    $( "#dialogProjectile" ).dialog('open');
  });

  $( "#dialogProjectile" ).dialog({
    autoOpen: false,

    modal: true,
    height: 400,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogProjectile" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_projectile('add.projectile.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Projectile Weapon",
        click: function() {
          attach_projectile('add.projectile.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Projectile Weapon",
        click: function() {
          attach_projectile('add.projectile.mysql.php', 0);
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

<form name="projectile">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Projectile Weapon Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('projectile-help');">Help</a></th>
</tr>
</table>

<div id="projectile-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Weapon Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickProjectile" value="Add Projectile Weapon"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Projectile Weapons...')?></span>

</div>


<div id="dialogProjectile" title="Projectile Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content" colspan="2">Class <select name="proj_class">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Projectile\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="proj_name" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content">Rating <input type="text" name="proj_rating" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">ACC <input type="text" name="proj_acc" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">Damage <input type="text" name="proj_damage" size="3"><input type="text" name="proj_type" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Use Strength? <input type="checkbox" name="proj_strength"></td>
</tr>
<tr>
  <td class="ui-widget-content">AP <input type="text" name="proj_ap" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Avail <input type="text" name="proj_avail" size="3"><input type="text" name="proj_perm" size="3"> Base Time <input type="text" name="proj_basetime" size="6"> Duration <select name="proj_duration">
<option value="0">Unset</option>
<?php
  $q_string  = "select dur_id,dur_name ";
  $q_string .= "from duration ";
  $q_string .= "order by dur_id ";
  $q_duration = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_duration = mysql_fetch_array($q_duration)) {
    print "<option value=\"" . $a_duration['dur_id'] . "\">" . $a_duration['dur_name'] . "</option>\n";
  }
?>
</select> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Street Index <input type="text" name="proj_index" size="6"> (sr3)</td>
</tr>
<tr>
  <td class="ui-widget-content">Cost <input type="text" name="proj_cost" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="proj_book">
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
</select> <input type="text" name="proj_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
