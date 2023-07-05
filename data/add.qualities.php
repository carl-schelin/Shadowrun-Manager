<?php
# Script: add.qualities.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.qualities.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Qualities</title>

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
function delete_qualities( p_script_url ) {
  var question;
  var answer;

  question  = "The Quality may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Quality?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.qualities.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_qualities(p_script_url, update) {
  var aq_form = document.dialog;
  var aq_url;

  aq_url  = '?update='   + update;
  aq_url += "&id="       + aq_form.id.value;

  aq_url += "&qual_name="      + encode_URI(aq_form.qual_name.value);
  aq_url += "&qual_value="     + encode_URI(aq_form.qual_value.value);
  aq_url += "&qual_desc="      + encode_URI(aq_form.qual_desc.value);
  aq_url += "&qual_book="      + encode_URI(aq_form.qual_book.value);
  aq_url += "&qual_page="      + encode_URI(aq_form.qual_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aq_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.qualities.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickQualities' ).click(function() {
    $( "#dialogQualities" ).dialog('open');
  });

  $( "#dialogQualities" ).dialog({
    autoOpen: false,

    modal: true,
    height: 225,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogQualities" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_qualities('add.qualities.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Qualities",
        click: function() {
          attach_qualities('add.qualities.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Qualities",
        click: function() {
          attach_qualities('add.qualities.mysql.php', 0);
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

<form name="qualities">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Qualities Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('qualities-help');">Help</a></th>
</tr>
</table>

<div id="qualities-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Qualities Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickQualities" value="Add New Quality"></td>
</tr>
</table>

</form>


<div id="tabs">

<ul>
  <li><a href="#positive">Positive Qualities</a></li>
  <li><a href="#negative">Negative Qualities</a></li>
</ul>


<div id="positive">

<span id="positive_table"><?php print wait_Process('Loading Positive Qualities...')?></span>

</div>


<div id="negative">

<span id="negative_table"><?php print wait_Process('Loading Negative Qualities...')?></span>

</div>

</div>


</div>

</div>


<div id="dialogQualities" title="Qualities">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.qualities.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
