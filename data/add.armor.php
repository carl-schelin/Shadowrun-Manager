<?php
# Script: add.armor.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.armor.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($db, $Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Clothing And Armor</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Johnson)) {
?>
function delete_armor( p_script_url ) {
  var question;
  var answer;

  question  = "The Armor may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Armor?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.armor.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_armor(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&arm_class="     + encode_URI(aa_form.arm_class.value);
  aa_url += "&arm_name="      + encode_URI(aa_form.arm_name.value);
  aa_url += "&arm_rating="    + encode_URI(aa_form.arm_rating.value);
  aa_url += "&arm_ballistic=" + encode_URI(aa_form.arm_ballistic.value);
  aa_url += "&arm_impact="    + encode_URI(aa_form.arm_impact.value);
  aa_url += "&arm_capacity="  + encode_URI(aa_form.arm_capacity.value);
  aa_url += "&arm_avail="     + encode_URI(aa_form.arm_avail.value);
  aa_url += "&arm_perm="      + encode_URI(aa_form.arm_perm.value);
  aa_url += "&arm_basetime="  + encode_URI(aa_form.arm_basetime.value);
  aa_url += "&arm_duration="  + encode_URI(aa_form.arm_duration.value);
  aa_url += "&arm_index="     + encode_URI(aa_form.arm_index.value);
  aa_url += "&arm_cost="      + encode_URI(aa_form.arm_cost.value);
  aa_url += "&arm_book="      + encode_URI(aa_form.arm_book.value);
  aa_url += "&arm_page="      + encode_URI(aa_form.arm_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.armor.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.armor.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickArmor' ).click(function() {
    $( "#dialogArmor" ).dialog('open');
  });

  $( "#dialogArmor" ).dialog({
    autoOpen: false,

    modal: true,
    height: 375,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogArmor" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_armor('add.armor.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Armor",
        click: function() {
          attach_armor('add.armor.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Armor",
        click: function() {
          attach_armor('add.armor.mysql.php', 0);
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

<form name="armor">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Armor And Clothing Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('armor-help');">Help</a></th>
</tr>
</table>

<div id="armor-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Ammunition Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addarm" id="clickArmor" value="Add Armor"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Armor...')?></span>

</div>


<div id="dialogArmor" title="Armor Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.armor.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
