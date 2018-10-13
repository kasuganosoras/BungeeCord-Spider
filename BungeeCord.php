<?php
/**
 *
 *	BungeeCord Jenkins Spider
 *
 *	by Akkariin
 *
 */
function curl_request($url, $post = '', $cookie = '', $returnCookie = 0) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	/** Use ShadowsocksR **/
	curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);  
	curl_setopt($curl, CURLOPT_PROXY, "192.168.3.231:2356");
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	curl_setopt($curl, CURLOPT_REFERER, $url);
	if ($post) {
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
	}
	if ($cookie) {
		curl_setopt($curl, CURLOPT_COOKIE, $cookie);
	}
	curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	if (curl_errno($curl)) {
		return curl_error($curl);
	}
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if($code !== 200) {
		$data = false;
	}
	curl_close($curl);
	return $data;
}
function Spider($root) {
	if(!file_exists("{$root}/BungeeCord/")) {
		mkdir("{$root}/BungeeCord");
		mkdir("{$root}/BungeeCord/plugins/");
	}
	echo date("[Y-m-d H:i:s") . " INFO] Checking new build versions...\n";
	$data = curl_request("https://ci.md-5.net/job/BungeeCord/rssAll");
	if(empty($data)) {
		echo date("[Y-m-d H:i:s") . " INFO] Version check failed!\n";
	}
	$data = simplexml_load_string($data);
	$data = json_decode(json_encode($data), true);
	foreach($data['entry'] as $build) {
		$link = $build['link']['@attributes']['href'];
		$vers = basename($link);
		echo date("[Y-m-d H:i:s") . " INFO] {$vers} Downloading...\n";
		if(file_exists("{$root}/BungeeCord/Bungeecord-{$vers}.jar") && file_exists("BungeeCord/plugins/{$vers}/")) {
			echo date("[Y-m-d H:i:s") . " INFO] {$vers} Already exist, continue.\n";
			continue;
		}
		if(!file_exists("{$root}/BungeeCord/plugins/{$vers}/")) {
			mkdir("{$root}/BungeeCord/plugins/{$vers}/");
		}
		echo date("[Y-m-d H:i:s") . " INFO] {$vers} Download status ";
		/** Download BungeeCord.jar **/
		download("{$root}/BungeeCord/Bungeecord-{$vers}.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/bootstrap/target/BungeeCord.jar");
		echo "=";
		/** Download Other **/
		download("{$root}/BungeeCord/plugins/{$vers}/cmd_alert.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/module/cmd-alert/target/cmd_alert.jar");
		echo "=";
		download("{$root}/BungeeCord/plugins/{$vers}/cmd_find.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/module/cmd-find/target/cmd_find.jar");
		echo "=";
		download("{$root}/BungeeCord/plugins/{$vers}/cmd_list.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/module/cmd-list/target/cmd_list.jar");
		echo "=";
		download("{$root}/BungeeCord/plugins/{$vers}/cmd_send.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/module/cmd-send/target/cmd_send.jar");
		echo "=";
		download("{$root}/BungeeCord/plugins/{$vers}/cmd_server.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/module/cmd-server/target/cmd_server.jar");
		echo "=";
		download("{$root}/BungeeCord/plugins/{$vers}/reconnect_yaml.jar", "https://ci.md-5.net/job/BungeeCord/{$vers}/artifact/module/reconnect-yaml/target/reconnect_yaml.jar");
		echo "=\n";
		echo date("[Y-m-d H:i:s") . " INFO] {$vers} Download complate!\n";
	}
	echo date("[Y-m-d H:i:s") . " INFO] Build version check & download finished.\n";
}
function download($save, $url) {
	if(file_exists($save)) {
		return;
	}
	$data = curl_request($url);
	if($data) {
		@file_put_contents($save, $data);
	}
}
$root = "/data/wwwroot/cdn.tcotp.cn/download/server";
while(true) {
	/** Spider work **/
	Spider($root);
	/** Sleep a day **/
	sleep(86400);
}
