<?php
/**
 * Z-Blog with PHP
 * @author
 * @copyright (C) RainbowSoft Studio
 * @version 2.0 2013-06-14
 */
require '../function/c_system_base.php';

ob_clean();

$zbp->CheckGzip();
$zbp->StartGzip();

?>
var zbp = new ZBP({
	bloghost: "<?php echo $zbp->host; ?>",
	ajaxurl: "<?php echo $zbp->ajaxurl; ?>",
	cookiepath: "<?php echo $zbp->cookiespath; ?>",
	lang: {
		error: {
			72: "<?php echo $lang['error']['72']; ?>",
			29: "<?php echo $lang['error']['29']; ?>",
			46: "<?php echo $lang['error']['46']; ?>"
		}
	}
});

<?php
if (!isset($_GET['pluginonly'])) {
?>
$(function () {
	
	var $cpLogin = $(".cp-login").find("a");
	var $cpVrs = $(".cp-vrs").find("a");
	var $addoninfo = zbp.cookie.get("addinfo<?php echo str_replace('/', '', $zbp->cookiespath);?>");
	if (!$addoninfo) return;
	$addoninfo = JSON.parse($addoninfo);

	if ($addoninfo.chkadmin){
		$(".cp-hello").html("' . $zbp->lang['msg']['welcome'] . ' " + $addoninfo.useralias + " (" + $addoninfo.levelname  + ")");
		if ($cpLogin.length == 1 && $cpLogin.html().indexOf("[") > -1) { 
			$cpLogin.html("[<?php echo $zbp->lang['msg']['admin']; ?>]");
		} else {
			$cpLogin.html("<?php echo $zbp->lang['msg']['admin']; ?>");
		}
	}

	if($addoninfo.chkarticle){
		if ($cpLogin.length == 1 && $cpVrs.html().indexOf("[") > -1) {
			$cpVrs.html("[<?php echo $zbp->lang['msg']['new_article']; ?>]"); 
		} else {
			$cpVrs.html("<?php echo $zbp->lang['msg']['new_article']; ?>");
		}
		$cpVrs.attr("href", bloghost + "zb_system/cmd.php?act=ArticleEdt");
	}

	zbp.cookie.set("timezone", (new Date().getTimezoneOffset()/60)*(-1));

	if ($addoninfo.userid < 1){
		zbp.userinfo.output();
	}

});
<?php
}
foreach ($GLOBALS['Filter_Plugin_Html_Js_Add'] as $fpname => &$fpsignal) {$fpname();}

$s = ob_get_clean();
$m = md5($s);

header('Content-Type: application/x-javascript; charset=utf-8');
header('Etag: ' . $m);

if( isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == $m ){
	SetHttpStatusCode(304);
	die;
}
	
echo $s;

die();
?>