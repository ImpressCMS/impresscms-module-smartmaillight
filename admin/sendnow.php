<?

include_once("admin_header.php");
smart_xoops_cp_header();
smart_adminMenu(-1, _AM_SMLIGHT_SENDNOW);

smart_collapsableBar('sendnow', _AM_SMLIGHT_SENDNOW, _AM_SMLIGHT_SENDNOW_DSC);

include_once(XOOPS_ROOT_PATH . '/modules/smartmaillight/class/mailer.php');

$smartmaillight_mailer = new SmartmaillightMailer();
$logs=array();

$smartmaillight_mailer->execute();
echo '<ul>';
foreach($smartmaillight_mailer->getLogs() as $log) {
	echo '<li>' . $log . '</li>';
}
echo '</ul>';
smart_close_collapsable('sendnow');
smart_modFooter();
xoops_cp_footer();
?>