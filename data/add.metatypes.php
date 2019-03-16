<?php
# Script: add.metatypes.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.metatypes.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Metatypes</title>

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
function delete_metatypes( p_script_url ) {
  var question;
  var answer;

  question  = "The Metatype may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Metatype?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.metatypes.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_metatypes(p_script_url, update) {
  var am_form = document.dialog;
  var am_url;

  am_url  = '?update='   + update;
  am_url += "&id="       + am_form.id.value;

  am_url += "&meta_name="   + encode_URI(am_form.meta_name.value);
  am_url += "&meta_walk="   + encode_URI(am_form.meta_walk.value);
  am_url += "&meta_run="    + encode_URI(am_form.meta_run.value);
  am_url += "&meta_swim="   + encode_URI(am_form.meta_swim.value);
  am_url += "&meta_notes="  + encode_URI(am_form.meta_notes.value);
  am_url += "&meta_book="   + encode_URI(am_form.meta_book.value);
  am_url += "&meta_page="   + encode_URI(am_form.meta_page.value);

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.metatypes.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickMetatype' ).click(function() {
    $( "#dialogMetatype" ).dialog('open');
  });

  $( "#dialogMetatype" ).dialog({
    autoOpen: false,

    modal: true,
    height: 200,
    width:  700,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogMetatype" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_metatypes('add.metatypes.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Metatype",
        click: function() {
          attach_metatypes('add.metatypes.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Metatype",
        click: function() {
          attach_metatypes('add.metatypes.mysql.php', 0);
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

<form name="metatypes">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Metatype Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('metatypes-help');">Help</a></th>
</tr>
</table>

<div id="metatypes-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Metatype Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickMetatype" value="Add Metatype"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Metatypes...')?></span>

</div>


<div id="dialogMetatype" title="Metatypes">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="6">Metatype Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Name <input type="text" name="meta_name" size="20"></td>
  <td class="ui-widget-content">Walk <input type="text" name="meta_walk" size="3"></td>
  <td class="ui-widget-content">Run <input type="text" name="meta_run" size="3"></td>
  <td class="ui-widget-content">Swim <input type="text" name="meta_swim" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Notes <input type="text" name="meta_notes" size="40"></td>
  <td class="ui-widget-content">Book  <select name="meta_book">
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
</select>: <input type="text" name="meta_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
