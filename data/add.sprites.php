<?php
# Script: add.sprites.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.sprites.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Sprites</title>

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
function delete_sprite( p_script_url ) {
  var question;
  var answer;

  question  = "The Sprite may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Sprite?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.sprite.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_sprite(p_script_url, update) {
  var as_form = document.dialog;
  var as_url;

  as_url  = '?update='   + update;
  as_url += "&id="       + as_form.id.value;

  as_url += "&sprite_name="         + encode_URI(as_form.sprite_name.value);
  as_url += "&sprite_attack="       + encode_URI(as_form.sprite_attack.value);
  as_url += "&sprite_sleaze="       + encode_URI(as_form.sprite_sleaze.value);
  as_url += "&sprite_data="         + encode_URI(as_form.sprite_data.value);
  as_url += "&sprite_firewall="     + encode_URI(as_form.sprite_firewall.value);
  as_url += "&sprite_initiative="   + encode_URI(as_form.sprite_initiative.value);
  as_url += "&sprite_book="         + encode_URI(as_form.sprite_book.value);
  as_url += "&sprite_page="         + encode_URI(as_form.sprite_page.value);

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.sprites.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickSprite' ).click(function() {
    $( "#dialogSprite" ).dialog('open');
  });

  $( "#dialogSprite" ).dialog({
    autoOpen: false,

    modal: true,
    height: 180,
    width:  700,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogSprite" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_sprite('add.sprites.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Sprite",
        click: function() {
          attach_sprite('add.sprites.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Sprite",
        click: function() {
          attach_sprite('add.sprites.mysql.php', 0);
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
  <th class="ui-state-default">Sprite Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('sprite-help');">Help</a></th>
</tr>
</table>

<div id="sprite-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>sprite Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickSprite" value="Add Sprite"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Sprites...')?></span>

</div>


<div id="dialogSprite" title="Sprites">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="6">Metatype Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">Name <input type="text" name="sprite_name" size="30"></td>
  <td class="ui-widget-content">Attack <input type="text" name="sprite_attack" size="3"></td>
  <td class="ui-widget-content">Sleaze <input type="text" name="sprite_sleaze" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Data Processing <input type="text" name="sprite_data" size="3"></td>
  <td class="ui-widget-content">Firewall <input type="text" name="sprite_firewall" size="3"></td>
  <td class="ui-widget-content">Initiative <input type="text" name="sprite_initiative" size="3"></td>
  <td class="ui-widget-content">Book <select name="sprite_book">
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
</select>: <input type="text" name="sprite_page" size="3"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
