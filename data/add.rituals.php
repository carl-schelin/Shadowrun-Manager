<?php
# Script: add.rituals.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.rituals.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Rituals</title>

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
function delete_ritual( p_script_url ) {
  var question;
  var answer;

  question  = "The Ritual may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Ritual?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.rituals.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_ritual(p_script_url, update) {
  var ar_form = document.dialog;
  var ar_url;

  ar_url  = '?update='   + update;
  ar_url += "&id="       + ar_form.id.value;

  ar_url += "&rit_name="       + encode_URI(ar_form.rit_name.value);
  ar_url += "&rit_anchor="     + ar_form.rit_anchor.checked;
  ar_url += "&rit_link="       + ar_form.rit_link.checked;
  ar_url += "&rit_minion="     + ar_form.rit_minion.checked;
  ar_url += "&rit_spell="      + ar_form.rit_spell.checked;
  ar_url += "&rit_spotter="    + ar_form.rit_spotter.checked;
  ar_url += "&rit_threshold="  + encode_URI(ar_form.rit_threshold.value);
  ar_url += "&rit_length="     + ar_form.rit_length.value;
  ar_url += "&rit_duration="   + ar_form.rit_duration.value;
  ar_url += "&rit_book="       + encode_URI(ar_form.rit_book.value);
  ar_url += "&rit_page="       + encode_URI(ar_form.rit_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ar_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.rituals.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickRitual' ).click(function() {
    $( "#dialogRitual" ).dialog('open');
  });

  $( "#dialogRitual" ).dialog({
    autoOpen: false,

    modal: true,
    height: 375,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogRitual" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_ritual('add.rituals.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Ritual",
        click: function() {
          attach_ritual('add.rituals.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Ritual",
        click: function() {
          attach_ritual('add.rituals.mysql.php', 0);
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

<form name="rituals">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Ritual Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ritual-help');">Help</a></th>
</tr>
</table>

<div id="ritual-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Ritual Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickRitual" value="Add Ritual"></td>
</tr>
</table>

</form>


<span id="mysql_table"><?php print wait_Process('Loading Rituals...')?></span>

</div>


<div id="dialogRitual" title="Ritual">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.rituals.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
