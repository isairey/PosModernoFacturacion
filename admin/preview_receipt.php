<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Receipt Preview</title>
    <link type="text/css" href="../assets/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
    <link type="text/css" href="../assets/itsolution24/cssmin/main.css" type="text/css" rel="stylesheet">
	<style type="text/css">
		<?php 
		$template_id = $request->get['template_id'];
		echo html_entity_decode(get_the_postemplate($template_id,'template_css'));
		?>
	</style>
</head>
<body>
<?php
$statement = $db->prepare("SELECT `invoice_id` FROM `selling_info` WHERE `store_id` = ? AND `status` = ? ORDER BY `info_id` DESC");
$statement->execute(array(store_id(), 1));
$row = $statement->fetch(PDO::FETCH_ASSOC);
if ($row) {
	$data = get_postemplate_empty_data();
} else {
	$data = get_postemplate_data($row['invoice_id']);
}
include DIR_VENDOR.'parser/lex/lib/Lex/ArrayableInterface.php';
include DIR_VENDOR.'parser/lex/lib/Lex/ArrayableObjectExample.php';
include DIR_VENDOR.'parser/lex/lib/Lex/Parser.php';
include DIR_VENDOR.'parser/lex/lib/Lex/ParsingException.php';
$parser = new Lex\Parser();
$template = html_entity_decode(get_the_postemplate($template_id,'template_content'));
echo $parser->parse($template, $data);
?>
<br>
<div class="text-center" style="margin-bottom:20px;">
	<a href="receipt_template.php?template_id=<?php echo $template_id;?>">&larr; <?php echo trans('button_back');?></a>
</div>
</body>
</html>