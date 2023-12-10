<?php
# Script: add.knowledge.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.commlink.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Knowledge Skills</title>

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
function delete_knowledge( p_script_url ) {
  var question;
  var answer;

  question  = "The Skill may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Skill?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.knowledge.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_knowledge(p_script_url, update) {
  var ak_form = document.dialog;
  var ak_url;

  ak_url  = '?update='   + update;
  ak_url += "&id="       + ak_form.id.value;

  ak_url += "&know_name="          + encode_URI(ak_form.know_name.value);
  ak_url += "&know_attribute="     + ak_form.know_attribute.value;

  script = document.createElement('script');
  script.src = p_script_url + ak_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.knowledge.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.knowledge.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickKnowledge' ).click(function() {
    $( "#dialogKnowledge" ).dialog('open');
  });

  $( "#dialogKnowledge" ).dialog({
    autoOpen: false,
    modal: true,
    height: 175,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogKnowledge" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_knowledge('add.knowledge.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Knowledge Skill",
        click: function() {
          attach_knowledge('add.knowledge.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Knowledge Skill",
        click: function() {
          attach_knowledge('add.knowledge.mysql.php', 0);
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

<form name="knowledge">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Knowledge Skill Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('knowledge-help');">Help</a></th>
</tr>
</table>

<div id="knowledge-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Knowledge Skill Form</strong>
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
  <td class="button ui-widget-content"><input type="button" name="addknow" id="clickKnowledge" value="Add Knowledge Skill"></td>
</tr>
</table>

</form>


<div id="tabs">

<ul>
  <li><a href="#street">Street Knowledge</a></li>
  <li><a href="#academic">Academic Knowledge</a></li>
  <li><a href="#professional">Professional Knowledge</a></li>
  <li><a href="#interests">Interests</a></li>
</ul>

<div id="street">

<span id="street_table"><?php print wait_Process('Loading Street Knowledge Skills...')?></span>

</div>


<div id="academic">

<span id="academic_table"><?php print wait_Process('Loading Academic Knowledge Skills...')?></span>

</div>


<div id="professional">

<span id="professional_table"><?php print wait_Process('Loading Professional Knowledge Skills...')?></span>

</div>


<div id="interests">

<span id="interests_table"><?php print wait_Process('Loading Interests...')?></span>

</div>


</div>

</div>


<div id="dialogKnowledge" title="Knowledge Skills">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.knowledge.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
