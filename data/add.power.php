<?php
# Script: add.power.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.power.php";

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
<title>Manage Creature Powers</title>

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
function delete_power( p_script_url ) {
  var question;
  var answer;

  question  = "The Power may be in use by existing spirits. Only delete if you're sure it's unused.\n";
  question += "Delete this Power?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.power.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_power(p_script_url, update) {
  var ap_form = document.dialog;
  var ap_url;

  ap_url  = '?update='   + update;
  ap_url += "&id="       + ap_form.id.value;

  ap_url += "&pow_name="         + encode_URI(ap_form.pow_name.value);
  ap_url += "&pow_type="         + encode_URI(ap_form.pow_type.value);
  ap_url += "&pow_range="        + encode_URI(ap_form.pow_range.value);
  ap_url += "&pow_action="       + encode_URI(ap_form.pow_action.value);
  ap_url += "&pow_duration="     + encode_URI(ap_form.pow_duration.value);
  ap_url += "&pow_description="  + encode_URI(ap_form.pow_description.value);
  ap_url += "&pow_book="         + encode_URI(ap_form.pow_book.value);
  ap_url += "&pow_page="         + encode_URI(ap_form.pow_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.power.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickPower' ).click(function() {
    $( "#dialogPower" ).dialog('open');
  });

  $( "#dialogPower" ).dialog({
    autoOpen: false,

    modal: true,
    height: 300,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogPower" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_power('add.power.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Power",
        click: function() {
          attach_power('add.power.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Power",
        click: function() {
          attach_power('add.power.mysql.php', 0);
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

<form name="sprites">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Creature Power Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('power-help');">Help</a></th>
</tr>
</table>

<div id="power-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickPower" value="Add Power"></td>
</tr>
</table>

</form>

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Creature Power Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('power-listing-help');">Help</a></th>
</tr>
</table>

<div id="power-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="mysql_table"><?php print wait_Process('Loading Creature Powers...')?></span>

</div>


<div id="dialogPower" title="Powers">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.power.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
