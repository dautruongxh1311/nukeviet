<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 4/12/2010, 1:27
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) )
{
	die( 'Stop!!!' );
}

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_data . "_Sitemap_" . NV_CACHE_PREFIX . ".cache";
$pa = NV_CURRENTTIME - 7200;

if( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
{
	$url = unserialize( $cache );
}
else
{
	//$sql = "SELECT id, catid, publtime, alias FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE status=1 ORDER BY publtime DESC LIMIT 1000";
	$sdr->reset()
		->select('id, catid, publtime, alias')
		->from(NV_PREFIXLANG . "_" . $module_data . "_rows")
		->where('status=1')
		->order( 'publtime DESC' )
		->limit('1000');	
	$result = $db->query( $sdr->get() );

	$url = array();

	while( list( $id, $catid_i, $publtime, $alias ) = $db->sql_fetchrow( $result ) )
	{
		$catalias = $global_array_cat[$catid_i]['alias'];
		$url[] = array(
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $catalias . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
			'publtime' => $publtime
		);
	}

	$cache = serialize( $url );
	nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();

?>