<?php
/*
 * $Id
 * Module: SmartSection
 * Author: Sudhaker Raj <http://xoops.biz>
 * Licence: GNU
 */

// define SMARTMAILLIGHT_SEO_ENABLED in mainfile.php, possible values
//   are "rewrite" & "path-info"

function smartmaillight_seo_title($title='', $withExt=true) 
{
    $words = preg_split('/[^0-9a-z.]+/', strtolower($title), -1, PREG_SPLIT_NO_EMPTY);
    if (sizeof($words) > 0) 
    {
        $ret = implode($words, '-');
        if ($withExt) {
        	$ret .= '.html';
        }
    	return $ret;
    } 
    else 
        return '';
}

function smartmaillight_seo_genUrl($op, $id, $short_url="")
{
    if (defined('SMARTMAILLIGHT_SEO_ENABLED')) 
    {
    	if (! empty($short_url)) $short_url = $short_url . '.html';
    	
        if (SMARTMAILLIGHT_SEO_ENABLED == 'rewrite')
        {
            // generate SEO url using htaccess
            return XOOPS_URL."/pages.${op}.${id}/${short_url}";
        }
        else if (SMARTMAILLIGHT_SEO_ENABLED == 'path-info')
        {
            // generate SEO url using path-info
            return XOOPS_URL."/modules/smartmaillight/seo.php/${op}.${id}/${short_url}";
        }
        else 
        {
            die('Unknown SEO method.');
        }
    } 
    else 
    {
       // generate classic url
        switch ($op) {
            case 'category':
               return XOOPS_URL."/modules/smartmaillight/${op}.php?categoryid=${id}";
            case 'page':
            case 'print':
               return XOOPS_URL."/modules/smartmaillight/${op}.php?pageid=${id}";
            default:
                die('Unknown SEO operation.');
        }
    }
}
?>