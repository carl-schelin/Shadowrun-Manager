<?php
# Script: add.lifestyle.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.lifestyle.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Lifestyles</title>

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
function delete_lifestyle( p_script_url ) {
  var question;
  var answer;

  question  = "The Lifestyle may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Lifestyle?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.lifestyle.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_lifestyle(p_script_url, update) {
  var al_form = document.dialog;
  var al_url;

  al_url  = '?update='   + update;
  al_url += "&id="       + al_form.id.value;

  al_url += "&life_style="           + encode_URI(al_form.life_style.value);
  al_url += "&life_mincost="         + encode_URI(al_form.life_mincost.value);
  al_url += "&life_maxcost="         + encode_URI(al_form.life_maxcost.value);
  al_url += "&life_book="            + encode_URI(al_form.life_book.value);
  al_url += "&life_page="            + encode_URI(al_form.life_page.value);

  script = document.createElement('script');
  script.src = p_script_url + al_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.lifestyle.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickLifestyle' ).click(function() {
    $( "#dialogLifestyle" ).dialog('open');
  });

  $( "#dialogLifestyle" ).dialog({
    autoOpen: false,

    modal: true,
    height: 225,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogLifestyle" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_lifestyle('add.lifestyle.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Lifestyle",
        click: function() {
          attach_lifestyle('add.lifestyle.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Lifestyle",
        click: function() {
          attach_lifestyle('add.lifestyle.mysql.php', 0);
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

<form name="lifestyle">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Lifestyle Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('lifestyle-help');">Help</a></th>
</tr>
</table>

<div id="lifestyle-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Lifestyle Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickLifestyle" value="Add Lifestyle"></td>
</tr>
</table>

</form>

<span id="mysql_table"><?php print wait_Process('Loading Lifestyles...')?></span>

</div>


<div id="dialogLifestyle" title="Lifestyle Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.lifestyle.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
