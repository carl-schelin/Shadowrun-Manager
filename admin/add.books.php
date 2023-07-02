<?php
# Script: add.books.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.books.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Books</title>

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
function delete_book( p_script_url ) {
  var question;
  var answer;

  question  = "Many items are likely associated with this book. Only delete if you're sure it's unused.\n";
  question += "Delete this Book?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.books.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_book(p_script_url, update) {
  var ab_form = document.dialog;
  var ab_url;

  ab_url  = '?update='   + update;
  ab_url += "&id="       + ab_form.id.value;

  ab_url += "&ver_book="     + encode_URI(ab_form.ver_book.value);
  ab_url += "&ver_short="    + encode_URI(ab_form.ver_short.value);
  ab_url += "&ver_core="     + ab_form.ver_core.checked;
  ab_url += "&ver_version="  + encode_URI(ab_form.ver_version.value);
  ab_url += "&ver_year="     + encode_URI(ab_form.ver_year.value);
  ab_url += "&ver_active="   + ab_form.ver_active.checked;
  ab_url += "&ver_admin="    + ab_form.ver_admin.checked;

  script = document.createElement('script');
  script.src = p_script_url + ab_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.books.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.books.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickBook' ).click(function() {
    $( "#dialogBook" ).dialog('open');
  });

  $( "#dialogBook" ).dialog({
    autoOpen: false,
    modal: true,
    height: 300,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogBook" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_book('add.books.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Book",
        click: function() {
          attach_book('add.books.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Book",
        click: function() {
          attach_book('add.books.mysql.php', 0);
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

<form name="books">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Book Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('book-help');">Help</a></th>
</tr>
</table>

<div id="book-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Active Skill Form</strong>
  <ul>
    <li><strong>Type</strong> - Tyep type of the active skill.</li>
    <li><strong>Name</strong> - The name of the skill.</li>
    <li><strong>Group</strong> - The group of the skill.</li>
    <li><strong>Attribute</strong> - The linked attribute for the skill.</li>
    <li><strong>Default</strong> - Whether the skill can be defaulted (-1).</li>
    <li><strong>Book</strong> - Select the book where this skill is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addbook" id="clickBook" value="Add Book"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#sr1">Shadowrun 1st</a></li>
  <li><a href="#sr2">Shadowrun 2nd</a></li>
  <li><a href="#sr3">Shadowrun 3rd</a></li>
  <li><a href="#sr4">Shadowrun 4th</a></li>
  <li><a href="#sr4a">Shadowrun 4th 20th Anniversary</a></li>
  <li><a href="#sr5">Shadowrun 5th</a></li>
  <li><a href="#sr6">Shadowrun 6th World</a></li>
</ul>


<div id="sr1">

<span id="sr1_table"><?php print wait_Process('Loading Shadowrun 1 Books...')?></span>

</div>


<div id="sr2">

<span id="sr2_table"><?php print wait_Process('Loading Shadowrun 2 Books...')?></span>

</div>


<div id="sr3">

<span id="sr3_table"><?php print wait_Process('Loading Shadowrun 3 Books...')?></span>

</div>


<div id="sr4">

<span id="sr4_table"><?php print wait_Process('Loading Shadowrun 4 Books...')?></span>

</div>


<div id="sr4a">

<span id="sr4a_table"><?php print wait_Process('Loading Shadowrun 4a Books...')?></span>

</div>


<div id="sr5">

<span id="sr5_table"><?php print wait_Process('Loading Shadowrun 5 Books...')?></span>

</div>


<div id="sr6">

<span id="sr6_table"><?php print wait_Process('Loading Shadowrun 6 Books...')?></span>

</div>


</div>

</div>


<div id="dialogBook" title="Book Form">

<form name="dialog">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content" colspan="5">Book <input type="text" name="ver_book" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Acronym <input type="text" name="ver_short" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Release <input type="text" name="ver_version" size="5"></td>
</tr>
<tr>
  <td class="ui-widget-content">Edition Year <input type="text" name="ver_year" size="6"></td>
</tr>
<tr>
  <td class="ui-widget-content">Core? <input type="checkbox" name="ver_core"></td>
</tr>
<tr>
  <td class="ui-widget-content">Active? <input type="checkbox" name="ver_active"></td>
</tr>
<tr>
  <td class="ui-widget-content">Admin? <input type="checkbox" name="ver_admin"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
