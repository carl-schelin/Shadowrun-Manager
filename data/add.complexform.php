<?php
# Script: add.complexform.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.complexform.php";

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
<title>Manage Complex Forms</title>

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
function delete_complexform( p_script_url ) {
  var question;
  var answer;

  question  = "The Complex Form may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Spell?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.complexform.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_complexform(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&form_name="        + encode_URI(ac_form.form_name.value);
  ac_url += "&form_target="      + encode_URI(ac_form.form_target.value);
  ac_url += "&form_duration="    + encode_URI(ac_form.form_duration.value);
  ac_url += "&form_level="       + encode_URI(ac_form.form_level.value);
  ac_url += "&form_fading="      + encode_URI(ac_form.form_fading.value);
  ac_url += "&form_book="        + encode_URI(ac_form.form_book.value);
  ac_url += "&form_page="        + encode_URI(ac_form.form_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.complexform.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickForm' ).click(function() {
    $( "#dialogForm" ).dialog('open');
  });

  $( "#dialogForm" ).dialog({
    autoOpen: false,
    modal: true,
    height: 275,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogForm" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_complexform('add.complexform.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Complex Form",
        click: function() {
          attach_complexform('add.complexform.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Complex Form",
        click: function() {
          attach_complexform('add.complexform.mysql.php', 0);
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

<form name="complexform">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Complex Form Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('complexform-help');">Help</a></th>
</tr>
</table>

<div id="spells-help" style="<?php print $display; ?>">

<div class="complexform-help ui-widget-content">


</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addform" id="clickForm" value="Add complex Form"></td>
</tr>
</table>

</form>

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Complexform Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('complexform-listing-help');">Help</a></th>
</tr>
</table>

<div id="complexform-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="complexform_table"><?php print wait_Process('Loading Complex Forms...')?></span>

</div>


</div>

</div>


<div id="dialogForm" title="Resonance Library">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.complexform.dialog.php');
?>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
