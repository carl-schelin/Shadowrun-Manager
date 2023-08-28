<?php
# Script: add.melee.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.melee.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Melee Weapons</title>

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
function delete_melee( p_script_url ) {
  var question;
  var answer;

  question  = "The Melee Weapon may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Melee Weapon?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.melee.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_melee(p_script_url, update) {
  var am_form = document.dialog;
  var am_url;

  am_url  = '?update='   + update;
  am_url += "&id="       + am_form.id.value;

  am_url += "&melee_class="      + encode_URI(am_form.melee_class.value);
  am_url += "&melee_name="       + encode_URI(am_form.melee_name.value);
  am_url += "&melee_acc="        + encode_URI(am_form.melee_acc.value);
  am_url += "&melee_reach="      + encode_URI(am_form.melee_reach.value);
  am_url += "&melee_ar1="        + encode_URI(am_form.melee_ar1.value);
  am_url += "&melee_ar2="        + encode_URI(am_form.melee_ar2.value);
  am_url += "&melee_ar3="        + encode_URI(am_form.melee_ar3.value);
  am_url += "&melee_ar4="        + encode_URI(am_form.melee_ar4.value);
  am_url += "&melee_ar5="        + encode_URI(am_form.melee_ar5.value);
  am_url += "&melee_damage="     + encode_URI(am_form.melee_damage.value);
  am_url += "&melee_weight="     + encode_URI(am_form.melee_weight.value);
  am_url += "&melee_type="       + encode_URI(am_form.melee_type.value);
  am_url += "&melee_flag="       + encode_URI(am_form.melee_flag.value);
  am_url += "&melee_conceal="    + encode_URI(am_form.melee_conceal.value);
  am_url += "&melee_strength="   + am_form.melee_strength.checked;
  am_url += "&melee_half="       + am_form.melee_half.checked;
  am_url += "&melee_ap="         + encode_URI(am_form.melee_ap.value);
  am_url += "&melee_avail="      + encode_URI(am_form.melee_avail.value);
  am_url += "&melee_perm="       + encode_URI(am_form.melee_perm.value);
  am_url += "&melee_basetime="   + encode_URI(am_form.melee_basetime.value);
  am_url += "&melee_duration="   + encode_URI(am_form.melee_duration.value);
  am_url += "&melee_index="      + encode_URI(am_form.melee_index.value);
  am_url += "&melee_cost="       + encode_URI(am_form.melee_cost.value);
  am_url += "&melee_book="       + encode_URI(am_form.melee_book.value);
  am_url += "&melee_page="       + encode_URI(am_form.melee_page.value);

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.melee.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickMelee' ).click(function() {
    $( "#dialogMelee" ).dialog('open');
  });

  $( "#dialogMelee" ).dialog({
    autoOpen: false,

    modal: true,
    height: 450,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogMelee" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_melee('add.melee.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Melee Weapon",
        click: function() {
          attach_melee('add.melee.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Melee Weapon",
        click: function() {
          attach_melee('add.melee.mysql.php', 0);
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

<form name="melee">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Melee Weapon Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('melee-help');">Help</a></th>
</tr>
</table>

<div id="melee-help" style="display: none">

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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickMelee" value="Add Melee Weapon"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Melee Weapons...')?></span>

</div>


<div id="dialogMelee" title="Melee Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.melee.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
