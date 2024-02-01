<?php
/*
* Le code a été écrit pour faire face aux dernières attaques sur les boutiques PhenixSuite/Prestashop
* Original code : psmoduly.cz/openservis.cz
* Ce code est open source, distribuez-le comme vous le souhaitez.
* Est mis à jour régulièrement à mesure que de nouvelles connaissances émergent
* Copyright @eolia eolia@eoliashop.com since 23/07/2022
* see https://malwaredecoder.com/ to test malicious codes
*/


$version = '3.1.3';

global $root_path, $root_directory, $limited;
$initial_time_out = ini_get('max_execution_time');
if(ini_set('max_execution_time', -1) === false) {
    $max_execution_time = $initial_time_out;
}
else {
    $max_execution_time =  '-1';
}
$limited = false;
$memory = memoryTest();
$memory_limit = $memory['memory_limit'];
if(($max_execution_time != '-1' && ($max_execution_time < 300)) 
    || ($memory_limit != '-1' && ($memory_limit < (512 * 1024 * 1024)))
) {
    $limited = true;
}

require_once (dirname(__file__) .'/config/config.inc.php');
if(version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {$limited = true;}
@ini_set('default_socket_timeout', 300);
@date_default_timezone_set('Europe/Paris');
register_shutdown_function('postmortem');
$start_time = microtime(true);
$ok = ($memory_limit == '-1') || ($memory_limit >= 512 * 1024 * 1024); // at least 512M?
if(!$ok) {
    die('La mémoire disponible sur votre serveur ('.$memory['display'].') est insuffisante pour exécuter ce script, veuillez l\'augmenter à 512 MB au minimum');
}
if(version_compare(phpversion(), '7.3.0', '<')) {
    @ini_set('pcre.jit',0);
    @ini_set('pcre.backtrack_limit',2148576);
    @ini_set('pcre.recursion_limit',2148576);
}
else {
    @ini_set('pcre2.jit',0);
    @ini_set('pcre2.backtrack_limit',2148576);
    @ini_set('pcre2.recursion_limit',2148576);
}
if(function_exists('newrelic_ignore_transaction')) {
    newrelic_ignore_transaction();
}
$root_path = getcwd().'/';
$root_directory = basename($root_path);
$updating = false;
$protocol = $_SERVER['HTTPS'] ? 'https://' : 'http://';
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
    'http' => array(
       'header' => array("Referer: ".$protocol.$_SERVER['HTTP_HOST'])
   )
);
$admin_dir = false;
$found = $files = 0;
header('Proceed: '.__LINE__);
$directory = new RecursiveDirectoryIterator('.');
$directory = new DirFilter($directory);
$scan = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
$admins = [];
foreach($scan as $file) {
    $files++;
    if($file->getFilename() == 'get-file-admin.php') {
        $found++;
        $admins[] = dirname($file);
        if($found == 1) {
            $admin_dir = str_replace('./', '', dirname($file));
        }
    }
}
$first_scan = 'Listing initial des '.$files.' fichiers effectué en '.round(microtime(true) - $start_time, 3).' sec. ';

$html = '<html itemscope="" itemtype="https://schema.org/QAPage" lang="fr">
        <head>
            <style>
                .slide-out{display: none;position: absolute;background-color:white;color:black;padding:20px 30px;max-height: 600px;left:50%;top:20px;transform:translate(-50%);overflow:auto;z-index:2;word-break:break-word;white-space:break-spaces;min-width:60%;max-width:90%;}
            </style>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
            <meta name="apple-mobile-web-app-capable" content="yes">
            <meta name="apple-mobile-web-app-status-bar-style" content="default">
            <meta name="mobile-web-app-capable" content="yes">
            <meta name="HandheldFriendly" content="True">
            <meta name="MobileOptimized" content="320">
            <meta name="title" content="Cleaner by @eolia">
        </head>
        <body style="background:black;padding:15px 15px 30px;color:white;">
        <pre><font color="white"><h3 style="font-size: 1.3em;color:white;">Script de nettoyage et contrôle pour boutiques PhenixSuite/Prestashop by @eolia, version ' . $version .'<br/><span style="font-size:0.7em;">Ce script est fourni gracieusement et en aucun cas son utilisation ne peut être payante ou facturée. Concernant les versions 8 et suivantes le contrôle d\'intégrité n\'est plus effectué.</span></h3>'.PHP_EOL;
if($found > 1) {
    die($html.'<span style="color:red; font-size:1.5em;">Plusieurs répertoires de type /admin ont été trouvés.<br/>Veuillez supprimer ceux qui sont inutiles ou le script ne saura pas lequel analyser<br/>'.print_r($admins, 1).'</span></pre></body></html>');
}
if(!$admin_dir) {
    die($html.'<span style="color:red">CMS inconnu. Script interrompu</span></pre></body></html>');
}
$encoded = substr(md5($root_path . $admin_dir), 0, 12);
$latest_filename = $encoded.'.php';
$current_url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
$current_script = basename($current_url);
$new_url = str_replace($current_script, $latest_filename, $current_url);
$suspicious_zip = new ZipArchive();
$old_zip_filename = $root_path.'suspicious.zip';
$daily_zip = $root_path.'suspicious_'.$encoded.'-'.date('Y-m-d').'.zip';
$list = glob($root_path.'suspicious_'.$encoded.'-*zip');
foreach($list as $file) {
    if(time() - filemtime($file) > 86400*7) {
        unlink($file);
    }
}
$zip_filename = $root_path.'suspicious_'.$encoded.'.zip';
$zip_url = str_replace($latest_filename, 'suspicious_'.$encoded.'.zip', $current_url);
if(file_exists($old_zip_filename)) {
    @unlink($zip_filename);
}
if(file_exists($zip_filename)) {
    @unlink($zip_filename);
}
$upd_fm_message = '';
if(isset($_POST['update_filemanager'])) {
    $zip = 'https://devcustom.net/public/scripts/filemanager.zip';
    $ext = pathinfo($zip, PATHINFO_EXTENSION);
    $fm_zip = tempnam(sys_get_temp_dir(), $ext);
    copy($zip, $fm_zip, stream_context_create($arrContextOptions));
    if(file_exists($fm_zip) && strlen($fm_zip)) {
        $zip = new ZipArchive;
        if($zip->open($fm_zip) === TRUE) {
            cleanDirectory($root_path.$admin_dir.'/filemanager');
            $zip->extractTo($root_path.$admin_dir);
            $zip->close();
            $upd_fm_message = '<span style="color:#08db08">Le répertoire '.$root_directory.'/**admin**/filemanager a été patché avec succès.</span>'.PHP_EOL.PHP_EOL;
        }
        else {
            $upd_fm_message = '<span style="color:red">Impossible de mettre à jour le répertoire '.$root_directory.'/**admin**/filemanager</span>'.PHP_EOL.PHP_EOL;
        }
    }
    @unlink($fm_zip);
}
if(!preg_match('/modules/i', $root_path) && file_exists('init.php')) {
    header('Proceed: '.__LINE__);
    $fgc = @file_get_contents('https://devcustom.net/public/cleaner.txt', false, stream_context_create($arrContextOptions));
    if($fgc) {
        if($current_script != $latest_filename || preg_match('/version = \'(.+)\'/i', $fgc, $matches)) {
            if($current_script != $latest_filename || version_compare($matches[1], $version, '>')) {
                if(preg_match('/class_index\.php/', $fgc)) {
                    if(!file_put_contents($latest_filename, $fgc.PHP_EOL .'/* Version téléchargée depuis devcustom.net - ' . date('Y-m-d H:i:s') .'*/')) {
                        die('<span style="color:red">Impossible de mettre à jour le fichier. Permissions en écriture insuffisantes</span></pre></body></html>');
                    }
                    else {
                        if($current_script == 'cleaner.php') {
                            file_put_contents('cleaner.php', $fgc);
                        }
                        if($current_script != $latest_filename) {
                            if(file_exists($root_path.$current_script))
                                unlink($root_path.$current_script);
                            $list = glob($root_path.'suspicious_*zip');
                            foreach($list as $file) {
                                unlink($file);
                            }
                        }
                    }
                    if(file_exists($latest_filename)) {
                        header('Refresh: 1; url='.$new_url);
                        echo $html;
                        echo $upd_fm_message;
                        echo '<span style="color:red">Votre version doit être mise à jour. Téléchargement de la dernière version '.(isset($matches) ? $matches[1] : '').' et exécution...</span></pre>';
                        sleep(2);
                        $updating = true;
                    }
                }
            }
            else {
                $html .= $upd_fm_message;
                $html .= '<p style="color:white;margin-top: 0;">Mémoire OK ('.$memory['display'].'). Vous avez la dernière version à jour du script -> Démarrage...<br/>Vous pouvez créer une tache cron dans le module cronjobs 1 fois par semaine en appelant '.$current_url.' automatiquement et recevoir le résultat par mail.</p>';
            }
        }
    }
    else {
        $html .= '<p style="color:white;margin-top: 0;">Mémoire OK. Contrôle de version impossible -> Démarrage avec la version actuelle...<br/>Vous pouvez créer une tache cron dans le module cronjobs 1 fois par semaine en appelant '.$current_url.' automatiquement et recevoir le résultat par mail.</p>';
    }
}
else
    die('<pre><span style="color:red">Ce script doit être placé à la racine de votre site (là où est installé votre Prestahop sur votre ftp) et nulle part ailleurs</span></pre></body></html>');
if(!$updating) {
    $html .= $first_scan.')<br/><br/>';
    //$array = iterator_to_array($scan, false);
    //ksort($array);
    //$scan = new ArrayIterator($array);
    if($current_script != 'cleaner.php') {
        $html .= '<h4 style="font-size: 1.1em;margin-bottom: 1em;margin-top: 0.3em;color:yellow">Par sécurité l\'url du script a été modifiée. Notez la nouvelle url si vous fermez cette page.<br/>Si vous avez oublié l\'url, relancez cleaner.php après l\'avoir re-téléchargé</h4>';
        if(file_exists($root_path.'cleaner.php')) {
            @unlink($root_path.'cleaner.php');
        }
    }
    $integrity = true;
    $zip_ok = true;
    if($suspicious_zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
        $zip_ok = false;
    }
    @ini_set('display_errors', 0);
    error_reporting(0);
    $md5_list = false;
    if(version_compare(_PS_VERSION_, '1.6.1.26', '>=') && version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
        $api_url = 'https://devcustom.net/public/scripts/xml/'._PS_VERSION_.'.json';
    }
    else {
        $api_url = 'https://md5.enter-solutions.com/json/'._PS_VERSION_.'.json';
    }
    header('Proceed: '.__LINE__);
    $md5_file = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
    if(version_compare(_PS_VERSION_, '8.0.0.0', '>')) {
        $md5_file = false;
    }
    if($md5_file) {
        $md5_list = json_decode($md5_file);
    }
    if(!is_object($md5_list)) {
        $md5_list = false;
    }
    $admin = Db::getInstance()->getRow('
        SELECT `email`, `lastname`, `firstname`
        FROM `'._DB_PREFIX_.'employee`
        WHERE `id_profile` = 1 AND `active` = 1
        ORDER BY '.(version_compare(_PS_VERSION_, '1.6.0.0', '>=') ? '`last_connection_date` DESC,' : '').' `id_employee`');
    $html .= '<span style="color:white;">Si des messages de nettoyage ou suppression sont affichés <b style="color:red">en rouge</b>, votre e-boutique est susceptible d\'avoir été <b>attaquée et a été protégée d\'urgence</b><br/>mais il est nécessaire de <b>RESTAURER les fichiers modifiés</b>, de <b>CHANGER le nom de votre répertoire admin et de <b>CHANGER les mots de passe des employés de votre boutique</b></span>.'.PHP_EOL.PHP_EOL;
    if(empty($md5_list)) {
        $integrity = false;
        $html .= '<b style="color:red">Fichiers source ignorés pour cette version: '._PS_VERSION_ .'. Les contrôles md5 ne seront pas effectués.</b><br/>';
    }
    else {
        header('Proceed: '.__LINE__);
    }
// START
    $force = true;
    $key = $bad = $bad1 = $bad2 = $bad3 = $found_admin = $error_js = 
    $bad_image = $error_line = $bad_found = $bad_infected = $bad_heuristic = 0;
    $indexes = $not_exist = $escaped = '';
    $full = isset($_POST['full']);
    $dot_files = '<h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 1.2em;color:white;">Recherche de fichiers .xxx ajoutés connus comme étant des infections:</h4>';
    $htaccess_files = '<h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 1.2em;color:white;">Recherche de fichiers htaccess ajoutés ou modifiés:</h4>';
    $admin_files = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Contrôle des fichiers admin:</h4>';
    $heuristic = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Contrôle sur les fichiers sensibles connus pour être modifiés:</h4>';
    $scripts = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Contrôle des scripts JS:</h4>';
    $images = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Contrôle des images pouvant contenir un script:</h4>';
    $infects = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Recherche des infections connues:</h4>';
    $bad_added_files = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Contrôle de sécurité sur fichiers indésirables connus:</h4>';
    $core_control = '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Contrôle de sécurité sur les fichiers php coeur:</h4>';
    $modules = '<h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 1.2em;color:white;">Recherche de vulnérabilité sur les modules (A titre d\'information. Si vous ne savez pas interpréter le code, veuillez demander l\'avis d\'un professionnel):</h4>';
    $to_be_monitored = 'config\/alias\.php|\/config\/config\.inc\.php|\/classes\/db\/Db\.php|\/classes\/Dispatcher\.php|\/classes\/Hook\.php|\/controllers\/front\/IndexController\.php|\/classes\/module\/Module\.php|\/classes\/controller\/FrontController\.php|\/tools\/smarty\/sysplugins\/smarty_internal_templatebase\.php';
    $unwanted = '\/XsamXadoo_Bot\.php|\/XsamXadoo_deface\.php|\/0x666\.php|\/f\.php|umcds\.php|\/eval-stdin\.php|\/Xsam_Xadoo\.html|\/xsamxadoo\.php|\/xsamxadoo1\.php|\/haxor\.php|\/h4x0r\.html|\/haxor\.html|\/phantom\.html|\/new_readme\.php|\/404\.php|\/wp-log\.php|\/a\.txt|\/archive\.php|\/Gass\.html|\/19855c\.php|\/gasshop\.php|\/getfile\.php|\/m\.php|\/m1\.php|\/moban\.html|\/popup-pomo\.php|\/up\.php|\/upload\.php|\/V2-plug\.php|\/xboo\.php\.png|\/xGASSx\.php|\/02ea|\/zaz\.php|\/zip\.php|\/hous\.php\.png|\/cache\/update\.php|\/classes\/tmp|\/classes\/module\/tmp|\/controllers\/front\/tmp|\/modules\/tmp|\/config\/tmp|\/Core\/tmp|\/ini\.php|\/web-right\.php|\/\.f26945b1\.ico|\/MARIJUANA\.php|\/jj\.php|\/syng\.php|\/new\.php|\/wp-login\.php|\/alfanew\.php|\.\/upgrade\.php|\/crack_self_restore\.php|\/checkbex\.php|\/down\.php|\/enfile\.php|\/gh\.php|\/pinuseren\.php|\/wp-head\.php|\/wp-site\.php|\/wp-info\.php|\/wp-admin\.php|\/wp-config-sample\.php|\/jiema\.php|\/cangma\.php|\/cawpf\.php|\/nowp\.php|\/nowpf\.php|\/cyborg_tmp\.php';
    $class_regex = '~
                ^\s*(?:abstract)?\s*(?:class|interface|trait)\s+
                (?P<class>\S+)[^{}]+(\{
                (?:[^{}]*|(?2))*
                \})~mx';
    $function_regex = '~
                ^\s*function \s+
                (?<name>\w+)\s*
                \((?<param>[^\)]*)\)\s*
                (?<body>\{(?:[^{}]+|(?&body))*\})
                ~mx';
    $re = '/(?:\/\*([^*]|(\*+([^*\/])))*\*\/)|(?:\/\/.*$)|(?:#.*$)|(?:\'(?:[^\'\\\\]|\\\\.)*\'|"(?:[^"\\\\]|\\\\.)*")/mU';
    foreach($scan as $file) {
        $count_files++;
        $filename = $file->getFilename();
        // Scan bad modules names
        header('Proceed: '.__LINE__);
        if(($filename != '.') && ($filename != '..') 
            && $file->isDir() 
            && (dirname($file) == './modules')
        ) {
            if(preg_match('~.+(?:_|__|\.old|_old|\.bak|\.zip|_bak|sauv_|bkp|-|--|gapi|ps_analytics)$~i', $file)) {
                $modules .= '<b style="color:orange">Répertoire indésirable détecté (à supprimer ou à sauvegarder ailleurs): '.str_replace('.', '', $file).'</b><br/>';
            }
            continue;
        }
        if(($filename != '.') && ($filename != '..') 
            && !$file->isDir()
        ) {
            if(preg_match('/\.\/themes\/|\.\/log\/|\/cache\/class_index\.php|\/sytem\/|\/sytems\/|\/cache\/classes\/index\.php/', $file)) {
                continue;
            }
            $path_parts = $file->getExtension();
            $script_name = str_replace('./', '', str_replace('./'.$admin_dir, 'admin', $file));
            if(!$full && filesize($file) > 450*1024) {
                if(in_array($path_parts, array('php', 'js', 'sql'))) {
                    $escaped .= '- '.$script_name.' ('.round(filesize($file)/1024).' Ko)<br/>';
                }
                continue;
            }
            // Integrity modules directory
            header('Proceed: '.__LINE__);
            if(dirname($file) == './modules') {
                if(($filename != '.htaccess') && !$path_parts) {
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    $fgc = file_get_contents($file);
                    $modules .= displayFileError('orange', 'Fichier sans extension détecté', $file, $fgc);
                    continue;
                }
                else {
                    if(!in_array($filename, array('index.php', '.htaccess'))) {
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        $fgc = file_get_contents($file);
                        if(!unlink($file)) {
                            $modules .= displayFileError('red', 'Fichier indésirable à supprimer impérativement (échec de la suppression):', $file, $fgc);
                        }
                        else {
                            $modules .= displayFileError('red', 'Elément indésirable supprimé dans /modules', $file, $fgc);
                        }
                        continue;
                    }
                }
            }
            // Bad dot files
            header('Proceed: '.__LINE__);
            if($path_parts == 'php') {
                $full_path = explode('/', $file->getPathname());
                $item_file = array_pop($full_path);
                if(is_array($full_path) && (count($full_path) > 1)) {
                    if($item_file == '.'.end($full_path).'.php') {
                        $bad1++;
                        $fgc = file_get_contents($file);
                        if(!unlink($file)) {
                            $dot_files .= displayFileError('red', 'Fichier dangereux à supprimer impérativement (échec de la suppression):', $file, $fgc);
                        }
                        else {
                            $dot_files .= displayFileError('red', 'Fichier dangereux supprimé', $file, $fgc);
                        }
                        continue;
                    }
                }
            }
            // Bad htaccess files
            header('Proceed: '.__LINE__);
            if($filename === '.htaccess') {
                $fgc = file_get_contents($file);
                if(preg_match('/suspected/i', $fgc, $mmatches, PREG_OFFSET_CAPTURE)) {
                    $bad2++;
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    if(filesize($file) < 150) {
                        if(!unlink($file)) {
                            $htaccess_files .= displayFileError('red', 'Fichier .htaccess ajouté à supprimer impérativement (échec de la suppression):', $file, $fgc);
                        }
                        else {
                            $htaccess_files .= displayFileError('red', 'Fichier .htaccess indésirable supprimé', $file, $fgc);
                        }
                    }
                    else {
                        $bad3++;
                        $htaccess_files .= displayFileError('red', 'Fichier .htaccess modifié à contrôler impérativement :', $file, $fgc);
                    }
                    continue;
                }
            }
            // Admin
            header('Proceed: '.__LINE__);
            if(preg_match('/\.\/'.$admin_dir.'\//', $file)) {
                if(preg_match('/filemanager/i', $file) && (version_compare(_PS_VERSION_, '1.6.0.0', '>='))) {
                    continue;
                }
                if(preg_match('/(cleaner|ajax-upgradetab|adminer|pfm|sytem|htaccess|autoupgrade)/i', $file)) {
                    continue;
                }
                $fgc = file_get_contents($file);
                if(!empty($md5_list->{$script_name}) && ($md5_list->{$script_name} != md5_file($file))) {
                    $content = $fgc;
                    if($filename == 'index.php') {
                        $content = cleanIndex($content);
                        if(empty($content)) {
                            $admin_files .= displayFileError('#08db08', 'Fichier modifié par rapport à la version d\'origine. Contenu OK', str_replace('admin', '**admin**', $script_name), $fgc);
                        }
                        else {
                            $found_admin++;
                            $integrity = false;
                            if($zip_ok) {
                                $suspicious_zip->addFile($file, $script_name);
                            }
                            if(preg_match('/(\/\*[0-9a-f]{5}\*\/|\\x[0-9a-f]{2,})/i', $content)) {
                                $admin_files .= displayFileError('red', 'MD5 ADMIN INTEGRITY : Fichier index.php infecté. Contenu à restaurer impérativement', str_replace('admin', '**admin**', $script_name), $content);
                            }
                            else {
                                $admin_files .= displayFileError('red', 'MD5 ADMIN WARNING : Fichier différent de l\'original', str_replace('admin', '**admin**', $script_name), $content);
                            }
                        }
                    }
                    else {
                        $found_admin++;
                        $integrity = false;
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        $admin_files .= displayFileError('orange', 'MD5 ADMIN WARNING : Fichier différent de l\'original', str_replace('admin', '**admin**', $script_name), $content);
                    }
                    continue;
                }
                if($md5_list && empty($md5_list->{$script_name})) {
                    if($filename == 'index.php') {
                        $content = cleanIndex($fgc);
                        if(empty($content)) {
                            $indexes .= displayFileError('#08db08', 'Fichier inexistant dans la version d\'origine. Contenu OK', str_replace('admin', '**admin**', $script_name), $fgc);
                        }
                        else {
                            $found_admin++;
                            if(preg_match('/(\/\*[0-9a-f]{5}\*\/|\\x[0-9a-f]{2,})/i', $content)) {
                                $indexes .= displayFileError('red', 'MD5 INTEGRITY : Fichier index.php infecté. Contenu à restaurer impérativement', str_replace('admin', '**admin**', $script_name), $content);
                            }
                            else {
                                $indexes .= displayFileError('red', 'Fichier inexistant dans la version d\'origine. Contenu à contrôler', str_replace('admin', '**admin**', $script_name), $content);
                            }
                        }
                    }
                    else {
                        $found_admin++;
                        $admin_files .= displayFileError('orange', 'Fichier inexistant dans la version d\'origine. Contenu à contrôler', str_replace('admin', '**admin**', $script_name), $fgc);
                    }
                    continue;
                }
            }
            // JS files
            header('Proceed: '.__LINE__);
            if(preg_match('/\.\/js\//', $file)) {
                if($path_parts == 'json') {
                    continue;
                }
                if(!empty($md5_list->{$script_name})) {
                    if($md5_list->{$script_name} != md5_file($file)) {
                        $fgc = file_get_contents($file);
                        $error_js++;
                        if($zip_ok && is_file($file)) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        $scripts .= displayFileError('red', 'MD5 ADMIN WARNING : Fichier différent de l\'original', $file, $fgc);
                    }
                    continue;
                }
                if(preg_match('~[a-zA-Z0-9]{5,5}.js~', $filename) || preg_match('~[0-9]{3,30}~', $filename)) {
                    $fgc = file_get_contents($file);
                    $error_js++;
                    if($zip_ok && is_file($file)) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    $fgc = file_get_contents($file);
                    if(preg_match('/eval\(function\(/i', $fgc) || preg_match('~(:?\\\\x[0-9A-F]{2}){10}~mi', $fgc)) {
                        $scripts .= '<b style="color:red">Fichier JS infecté trouvé dans: '.$file.'</b><br/>';
                        if($force) {
                            if($zip_ok) {
                                $suspicious_zip->addFile($file, $script_name);
                            }
                            if(unlink($file)) {
                                $scripts .= '<b style="color:#08db08">Fichier '.$script_name.' supprimé</b>'.PHP_EOL;
                                continue;
                            }
                        }
                    }
                    else {
                        $message = $md5_list ? 'Fichier JS ajouté, inexistant dans la version d\'origine' : 'Fichier JS à contrôler';
                        $scripts .= displayFileError('red', $message, $file, $fgc);
                    }
                }
                $filesize = filesize($file);
                if($filesize == 33637 || $filesize == 33082) {
                    $error_js++;
                    $scripts .= '<b style="color:red">Fichier JS infecté trouvé dans: '.$script_name.'</b><br/>';
                    if($force) {
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        if(unlink($file))
                            $scripts .= '<b style="color:#08db08">Fichier '.$script_name.' supprimé</b>'.PHP_EOL;
                    }
                    continue;
                }
            }
            // Images
            header('Proceed: '.__LINE__);
            if(preg_match('/\.\/img\//', $file)) {
                if(stripos(file_get_contents($file), 'PHNjcmlwd') !== false) {
                    $bad_image++;
                    $images .= '<b style="color:red">Fichier img infecté trouvé sur votre boutique: '.$file.'</b><br/>';
                    if($force) {
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        if(unlink($file)) {
                            $images .= 'Fichier '.$file.' supprimé'.PHP_EOL;
                        }
                    }
                }
                continue;
            }
            // Integrity
            header('Proceed: '.__LINE__);
            if($filename === 'defines.inc.php') {
                $fgc = file_get_contents($file);
                $lines = explode("\n", $fgc);
                foreach($lines as $key => $line) {
                    if(preg_match("/include_once\((.*)'\);/i", $line, $matches)) {
                        $bad_infected++;
                        $bad_heuristic++;
                        $integrity = false;
                        $matches[1] = str_replace('$'.'_SERVER[\'DOCUMENT_ROOT\'].', '', $matches[1]);
                        $infected = trim($matches[1], '/');
                        if(file_exists($infected)) {
                            $infects .= '<b style="color:red">Fichier indésirable détecté: ' . $infected .'</b><br/>';
                            $integrity = false;
                            if($force) {
                                if($zip_ok) {
                                    $suspicious_zip->addFile($infected, str_replace('.', '', $infected));
                                }
                                @unlink($infected);
                                if(file_exists($infected)) {
                                    $infects .= '<b style="color:red">Échec de la suppression de ce fichier - '.str_replace('.', '', $infected).' - contactez votre hébergeur pour le faire (le fichier est protégé contre la suppression)' .'</b><br/>';
                                }
                                else {
                                    $infects .= '<b style="color:#08db08">>>> Supprimé: '.$infected.'</b><br/>';
                                }
                            }
                        }
                        if($force) {
                            unset($lines[$key]);
                            $lines = implode("\n", $lines);
                            file_put_contents($file, $lines);
                            $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                        }
                        break 1;
                    }
                }
            }
            if($filename === 'AdminLoginController.php') {
                $fgc = file_get_contents($file);
                if(preg_match('/base64/i', $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    if($force) {
                        $fgc = preg_replace('/\$path(.*)PrestaShopLogger/ims', '/* Virus infection fixed already */ PrestaShopLogger', $fgc);
                        $integrity = false;
                        file_put_contents($file, $fgc);
                        $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                if(preg_match('/this->trans/i', $fgc) && preg_match('/displayError/i', $fgc) && !preg_match('/registerSession/i', $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    if($force) {
                        $fgc = str_replace("if(!Tools::getValue('stay_logged_in'))", "if(method_exists(\$cookie, 'registerSession')) {\$cookie->registerSession(new EmployeeSession());}if(!Tools::getValue('stay_logged_in'))", $fgc);
                        $integrity = false;
                        file_put_contents($file, $fgc);
                        $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
            }
            if($filename === 'Controller.php') {
                $fgc = file_get_contents($file);
                if(preg_match('/(.*)REQUEST_URI(.*)/', $fgc, $matches)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = str_replace($matches[0], 'if (0) '.$matches[0], $fgc);
                    $infects .= displayFileError('red', 'MD5 INTEGRITY >>>> Ligne modifiée: '.$matches[0], $file, $fgc);
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                if(preg_match('/=base64/i', $fgc) && !preg_match('/Virus infection fixed already/i', $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = preg_replace('/\$html.=base64_decode/ims', '/* Virus infection fixed already */ // $html.=base64_decode', $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                if(preg_match('/base64/i', $fgc) && preg_match('/_hash/i', $fgc) && !preg_match('/Virus_infection_fixed_already/i', $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = str_replace("_hash'", "_hash_commented_Virus_infection_fixed_already_" . uniqid() . "'", $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
            }
            if($filename === 'FrontController.php') {
                $fgc = file_get_contents($file);
                if(preg_match('/=base64/i', $fgc) && !preg_match('/Virus infection fixed already/i', $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = preg_replace('/\$html.=base64_decode/ims', '/* Virus infection fixed already */ // $html.=base64_decode', $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
            }
            if($filename === 'Db.php') {
                $fgc = file_get_contents($file);
                if(preg_match('/base64/i', $fgc) && !preg_match('/Virus_infection_fixed_already/i', $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = str_replace("_hash'", "_hash_commented_Virus_infection_fixed_already_" . uniqid() . "'", $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
            }
            if($filename === 'Module.php') {
                $fgc = file_get_contents($file);
                $search = "-2) == '')";
                $search_preg_match = "-2\) == ''\)";
                $fix = "-2) == '?>')";
                if(preg_match("/$search_preg_match/i", $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = str_replace($search, $fix, $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                $search = 'encoding="UTF-8"';
                $search_missing_preg_match = 'encoding="UTF-8" \?>';
                $search_preg_match = 'encoding="UTF-8"';
                $fix = 'encoding="UTF-8" ?>';
                if(preg_match("/$search_preg_match/i", $fgc) && !preg_match("/$search_missing_preg_match/i", $fgc)) {
                    $fgc = str_replace($search, $fix, $fgc);
                    $bad_infected++;
                    $bad_heuristic++;
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
            }
            if($filename === 'alias.php') {
                $fgc = file_get_contents($file);
                if(preg_match('/(.*)md5(.*)/i', $fgc, $matches)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $fgc = str_replace($matches[0], 'if (0) '.$matches[0], $fgc);
                    $infects .= displayFileError('red', 'MD5 INTEGRITY >>>> Ligne modifiée: '.$matches[0], $file, $fgc);
                    $integrity = false;
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
            }
            if($filename === 'Mage.php') {
                $bad_infected++;
                $infects .= '<b style="color:red">Fichier indésirable Mage.php détecté: '.$script_name.'</b><br/>';
                if($force) {
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    @unlink($file);
                    if(file_exists($file)) {
                        $integrity = false;
                        $infects .= '<b style="color:red">!!! Échec de la suppression de ce fichier - '.$script_name.' - contactez votre hébergeur pour le faire (le fichier est protégé contre la suppression)' .'</b><br/>';
                    }
                    else {
                        $infects .= '<b style="color:#08db08">>>> Fichier Mage.php supprimé' .'</b><br/>';
                    }
                }
                continue;
            }
            if($filename === 'smarty_internal_templatebase.php') {
                $fgc = file_get_contents($file);
                $search = 'eval("" . $';
                $search_preg_match = 'eval\(\"\" . \$';
                $fix = '/* Virus infection fixed already */ eval("?>" . $';
                if(preg_match("/$search_preg_match/i", $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $integrity = false;
                    $fgc = str_replace($search, $fix, $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                $search = "'/(<%|%>|<\?php|<\?|\)/'";
                $search_preg_match = preg_quote("'/(<%|%>|<\?php|<\?|\)/'", '/');
                $fix = "'/(<%|%>|<\?php|<\?|\?>)/'";
                if(preg_match("/$search_preg_match/i", $fgc)) {
                    $bad_infected++;
                    $bad_heuristic++;
                    $integrity = false;
                    $fgc = str_replace($search, $fix, $fgc);
                    $infects .= '<b style="color:red">>>> Corrigé: '.$script_name.'</b><br/>';
                    if($force) {
                        file_put_contents($file, $fgc);
                    }
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                continue;
            }
            // Unwanted
            header('Proceed: '.__LINE__);
            if(preg_match('/'.$unwanted.'/i', $file, $matches)) {
                if(version_compare(_PS_VERSION_, '1.6.0.0', '<') && ($file == './404.php')) {
                    continue;
                }
                if(file_exists($file)) {
                    $fgc = file_get_contents($file);
                    $bad_found++;
                    $bad_added_files .= displayFileError('red', 'Fichier indésirable détecté', $script_name, $fgc);
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    if(unlink($root_path.$file)) {
                        $bad_added_files .= '<b style="color:#08db08">Fichier '.$script_name.' indésirable supprimé</b><br/>';
                    }
                    else {
                        $bad_added_files .= '<b style="color:red">!!! Échec de la suppression de ce fichier - '.$script_name.' - contactez votre hébergeur pour le faire (le fichier est protégé contre la suppression)' .'</b><br/>';
                    }
                }
                continue;
            }
            // Files without extension
            header('Proceed: '.__LINE__);
            if(preg_match('/\.\/config\/|\.\/classes\/|\._/controllers\/|\.\/app\/|\.\/var\/|\.\/tools\//i', $file)) {
                if(!$path_parts) {
                    $fgc = file_get_contents($file);
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    $integrity = false;
                    $core_control .= displayFileError('orange', 'Fichier sans extension détecté', $script_name, $fgc);
                    continue;
                }
            }
            // Smarty patch
            header('Proceed: '.__LINE__);
            if(preg_match('/config\/smarty\.config\.inc\.php/i', $file)) {
                $fgc = file_get_contents($file);
                if(preg_match('/if \(Configuration::get\(\'PS_SMARTY_CACHING_TYPE\'\)/', $fgc, $matches)) {
                    $fgc = preg_replace('/if \(Configuration::get\(\'PS_SMARTY_CACHING_TYPE\'\)/', 'if(false && Configuration::get(\'PS_SMARTY_CACHING_TYPE\')', $fgc);
                    if($force) {
                        file_put_contents($file, $fgc);
                        $core_control .= '<b style="color:#08db08">Fichier '.$script_name.' patché avec succès (Injection SQL possible par cache Smarty)</b><br/>';
                    }
                }
                if(version_compare(_PS_VERSION_, '1.7.9.9', '<') && preg_match('~if\(false && Configuration::get~', $fgc)) {
                    $core_control .= '<b style="color:#08db08">Contrôle du patch (Injection SQL possible par cache Smarty) sur '.$script_name.' => OK</b><br/>';
                }
                continue;
            }
            // Core control
            header('Proceed: '.__LINE__);
            if(($md5_list != false)
                && !preg_match('/\.\/modules\//', $file)
            ) {
                $error2 = 0;
                $original = true;
                $fgc = file_get_contents($file);
                if(!empty($md5_list->{$script_name}) 
                    && ($md5_list->{$script_name} != md5_file($file))
                    && ($path_parts == 'php')
                ) {
                    if($filename == 'index.php') {
                        $content = cleanIndex(file_get_contents($file));
                        if(empty($content)) {
                            $core_control .= '<b style="color:#08db08">MD5 INTEGRITY : Fichier index.php modifié par rapport à la version d\'origine. Contenu OK: '.$script_name.'</b><br/>';
                        }
                        else {
                            $original = false;
                            $integrity = false;
                            $error2++;
                            if($zip_ok) {
                                $suspicious_zip->addFile($file, $script_name);
                            }
                            if(preg_match('/(\/\*[0-9a-f]{5}\*\/|\\x[0-9a-f]{2,})/i', $fgc))
                                $core_control .= displayFileError('red', 'MD5 INTEGRITY : Fichier index.php infecté. Contenu à restaurer impérativement', $script_name, $fgc);
                            else
                                $core_control .= displayFileError('red', 'MD5 INTEGRITY : Fichier index.php modifié par rapport à la version d\'origine. Contenu à contrôler', $script_name, $fgc);
                        }
                        continue;
                    }
                    else {
                        $original = false;
                        $integrity = false;
                        $error2++;
                        if(preg_match('/\$GLOBALS|\$_GET\[§\]|gzinflate\(substr\(/i', $fgc)) {
                            if(preg_match('/'.$to_be_monitored.'/', $file)) {
                                $bad_heuristic++;
                                $core_control .= displayFileError('red', 'MD5 INTEGRITY : Fichier php infecté. Contenu à restaurer impérativement', $script_name, $fgc);
                            }
                            else {
                                $heuristic .= displayFileError('red', 'MD5 INTEGRITY : Fichier php infecté. Contenu à restaurer impérativement', $script_name, $fgc);
                            }
                            if($zip_ok) {
                                $suspicious_zip->addFile($file, $script_name);
                            }
                        }
                        else {
                            if($zip_ok) {
                                $suspicious_zip->addFile($file, $script_name);
                            }
                            if(preg_match('/'.$to_be_monitored.'/', $file)) {
                                $bad_heuristic++;
                                $heuristic .= displayFileError('orange', 'Contrôle de '.$filename.' : Fichier php modifié par rapport à la version d\'origine', $script_name, $fgc);
                            }
                            else {
                                $core_control .= displayFileError('orange', 'MD5 INTEGRITY : Fichier php modifié par rapport à la version d\'origine. Contenu à contrôler', $root_directory.'/'.$script_name, $fgc);
                            }
                        }
                        continue;
                    }
                }
                if(($filename != $latest_filename) 
                    && !isset($md5_list->{$script_name}) 
                    && !preg_match('/modules\/|download\/|media\//', $file)
                    && ($path_parts == 'php')
                ) {
                    $copy = true;
                    if($filename == 'index.php') {
                        $content = cleanIndex($fgc);
                        if(!empty($content)) {
                            if(preg_match('/(\/\*[0-9a-f]{5}\*\/|\\x[0-9a-f]{2,})/i', $content))
                                $indexes .= displayFileError('red', 'MD5 INTEGRITY : Fichier index.php infecté. Contenu à restaurer impérativement', $script_name, $content);
                            else
                                $indexes .= displayFileError('orange', 'MD5 INTEGRITY : Fichier index.php ajouté par rapport à la version d\'origine. Contenu à contrôler', $script_name, $content);
                        }
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        continue;
                    }
                    $error2++;
                    $color2 = '#187ed8';
                    if(preg_match('/config\//', $file)) {
                        if($script_name == 'config/settings.inc.old.php') {
                            unlink($file);
                            continue;
                        }
                        $fgc = preg_replace('/(define\(\'.*?\', )([^)]*)(\);)/m', '$1XXXXXXX$3', $fgc);
                        if($zip_ok) {
                            $suspicious_zip->addFromString($script_name, $fgc);
                            $copy = false;
                        }
                    }
                    if(preg_match('#^(?:admin\/|js\/|img\/|cms\/|css\/|pdf\/|themes\/|tmp\/|upload\/|media\/|download\/|localization\/)#i', dirname($script_name))) {
                        $not_exist .= displayFileError('red', 'Fichier php suspect interdit dans ce répertoire', $script_name, $fgc);
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                        $img_name = str_replace('.php', '', $file);
                        if(unlink($file))
                            $not_exist .= '<b style="color:#08db08">Fichier '.$script_name.' indésirable supprimé</b><br/>';
                        else
                            $not_exist .= '<b style="color:red">!!! Échec de la suppression de ce fichier - '.$script_name.' - contactez votre hébergeur pour le faire (le fichier est protégé contre la suppression)' .'</b><br/>';
                        $img_files = glob($img_name.'.*');
                        foreach($img_files as $img_file) {
                            if($zip_ok) {
                                $suspicious_zip->addFile($img_file, basename($img_file));
                            }
                            $path_file = str_replace($root_path, '', $img_file);
                            if(unlink($img_file))
                                $not_exist .= '<b style="color:#08db08">Fichier '.$path_file.' indésirable supprimé</b><br/>';
                            else
                                $not_exist .= '<b style="color:red">!!! Échec de la suppression de ce fichier - '.$path_file.' - contactez votre hébergeur pour le faire (le fichier est protégé contre la suppression)' .'</b><br/>';
                        }
                        continue;
                    }
                    else {
                        if(preg_match('/move_uploaded_file|file_put_contents\(|fwrite|uhex|decoct|bnexazabi|0x5a455553|shaje3|gzuncompress|include\(|"ক"|"খ"|"গ"|"ঘ"|$_POST\[|file_get_contents\(|assert\(|github\.io|dyuweyrj|dyuweyrj4|pastebin|eval\(/iU', $fgc, $mmatches)) {
                            $color2 = 'red';
                            $not_exist .= displayFileError($color2, 'Fichier php inexistant dans la version d\'origine qui contient des fonctions permettant une injection ('.$mmatches[0].')', $script_name, $fgc);
                        }
                        else {
                            $color2 = 'orange';
                            $not_exist .= displayFileError($color2, 'Fichier php inexistant dans la version d\'origine. Contenu à contrôler', $script_name, $fgc);
                        }
                    }
                    if($copy && $zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                }
                if(!$original) {
                    $content_file = $fgc;
                    $origins = array();
                    $fgc = preg_replace_callback(
                            $re,
                            function($matches) use(&$origins) {
                                static $n = 0;
                                $n++;
                                $i = "\4".$n."\4";
                                if($matches[0][0] == '\'') { $origins[$i] = $matches[0]; return $i; }
                                if($matches[0][0] == '"') { $origins[$i] = $matches[0]; return $i; }
                                return '';
                            }
                            , $fgc
                        );
                    if(preg_match_all($class_regex, $fgc, $matches)) {
                        $test = $fgc;
                        foreach($matches[0] as $match) {
                            $test = str_replace($match, '', $test);
                        }
                        $test = trim(str_replace(array('<?php', '?>'), '', $test));
                        $test = trim(preg_replace('~^\s*(?:use|namespace)\s+.*$~mxi', '', $test));
                        if(preg_match_all($function_regex, $test, $fmatches)) {
                            foreach($fmatches[0] as $fmatch) {
                                $test = str_replace($fmatch, '', $test);
                            }
                            $test = trim($test);
                        }
                        foreach($origins as $k => &$m) {
                            $test = str_replace($k, $m, $test);
                        }
                        if(strlen($test)) {
                            $error2++;
                            $integrity = false;
                            $core_control .= displayFileError('orange', 'Lignes hors classe', $script_name, $test);
                            if(preg_match('/'.$to_be_monitored.'/', $file)) {
                                $bad_heuristic++;
                                $content_file = str_replace($test, '', $content_file);
                                if($force) {
                                    file_put_contents($file, $content_file);
                                    $core_control .= '<b style="color:red">Classe coeur corrigée: '.$script_name.'</b><br/>';
                                }
                            }
                        }
                    }
                }
                else {
                    if(!$error2 && preg_match('/'.$to_be_monitored.'/', $file)) {
                        $heuristic .= '<b style="color:#08db08">Contrôle de '.$filename.' => OK</b><br/>';
                    }
                }
            }
            // Modules
            header('Proceed: '.__LINE__);
            if(preg_match('/\.\/modules\//', $file)
                && ($path_parts == 'php')
                && !preg_match('/\/translations\/|upgrade\//', $file)
            ) {
                $original_content = $fgc = file_get_contents($file);
                if($filename == 'soracaisse') {
                    continue;
                }
                if(preg_match('/httpd\.conf|c3e34653504601739f7cbb3f79bb63d2|vhosts\.conf|fetchmailrc|Bruteforce|shell_exec|0x5aa5| find_bl\(| f_id_daww| DateStripeV| hex2a\(| sanitas\(| smenu\(|hashcracking\.ru|rednoize\.com|crackfor\.me/i', $fgc, $mmatches, PREG_OFFSET_CAPTURE)
                    || preg_match('/ WSO| wso| f_id_daww/', $fgc, $mmatches, PREG_OFFSET_CAPTURE)) {
                    $risk = $mmatches[0][0];
                    $bad++;
                    if($zip_ok) {
                        $suspicious_zip->addFile($file, $script_name);
                    }
                    if(!unlink($file)) {
                        $modules .= displayFileError('red', 'Fichier dangereux à supprimer impérativement (échec de la suppression):', $script_name, $original_content);
                    }
                    else {
                        $modules .= displayFileError('red', 'Fichier dangereux supprimé ('.$risk.')', $script_name, $original_content);
                    }
                    continue;
                }
                if(preg_match('/netstat|move_uploaded_file|exploit-db|github\.io|0x5a455553|file_put_contents|GLOBALS|fwrite|include%28|base64_decode|gzinflate|str_rot13|include\(|file_get_contents\(|fwrite|assert\(|dyuweyrj|dyuweyrj4|pastebin|hex2bin%28|hex2bin\(|eval%28|eval\(/i', $fgc, $mmatches, PREG_OFFSET_CAPTURE)) {
                    $risk = $mmatches[0][0];
                    $color_m = in_array($risk, array('eval%28','eval(','hex2bin%28','hex2bin(','str_rot13')) ? '#ff5900;' : 'orange;';
                    if(!$limited) {
                        $origins = array();
                        $fgc = preg_replace_callback(
                                $re,
                                function ($matches) use (&$origins) {
                                    static $n = 0;
                                    $n++;
                                    $i = "\4".$n."\4";
                                    if($matches[0][0] == '\'') { $origins[$i] = $matches[0]; return $i; }
                                    if($matches[0][0] == '"') { $origins[$i] = $matches[0]; return $i; }
                                    return '';
                                }
                                , $fgc
                            );
                    }
                    if(!$limited && preg_match_all($class_regex, $fgc, $matches)) {
                        $test = $fgc;
                        foreach($matches[0] as $match) {
                            $test = str_replace($match, '', $test);
                        }
                        $test = trim(str_replace(array('<?php', '?>'), '', $test));
                        $test = trim(preg_replace('~^\s*(?:use|namespace)\s+.*$~mxi', '', $test));
                        if(preg_match_all($function_regex, $test, $fmatches)) {
                            foreach($fmatches[0] as $fmatch) {
                                $test = str_replace($fmatch, '', $test);
                            }
                            $test = trim($test);
                        }
                        foreach($origins as $k => &$m) {
                            $test = str_replace($k, $m, $test);
                        }
                        if(preg_match('~'.preg_quote($risk,'~').'~', $test) && strlen($test)) {
                            $bad++;
                            $modules .= displayFileError($color_m, 'Fonction sensible hors classe à contrôler: '.$risk, $script_name, $fgc);
                            if($zip_ok) {
                                $suspicious_zip->addFile($file, $script_name);
                            }
                        }
                    }
                    else {
                        $bad++;
                        $modules .= displayFileError($color_m, 'Fonction sensible à contrôler: '.$risk.'', $script_name, $fgc);
                        if($zip_ok) {
                            $suspicious_zip->addFile($file, $script_name);
                        }
                    }
                }
            }
        }
    }
    if($zip_ok) {
        $suspicious_zip->close();
    }
    if(!file_exists($root_path.'img/.htaccess'))
        file_put_contents($root_path.'img/.htaccess', '<FilesMatch "\.php$">'.PHP_EOL.'Deny from all'.PHP_EOL.'</FilesMatch>');
    if(!file_exists($root_path.'localization/.htaccess'))
        file_put_contents($root_path.'localization/.htaccess', 'Order deny,allow'.PHP_EOL.'Deny from all');
    if(!file_exists($root_path.'translations/.htaccess'))
        file_put_contents($root_path.'translations/.htaccess', 'Order deny,allow'.PHP_EOL.'Deny from all');
    if(!file_exists($root_path.'themes/.htaccess'))
        file_put_contents($root_path.'themes/.htaccess', '<FilesMatch "\.php$">'.PHP_EOL.'Deny from all'.PHP_EOL.'</FilesMatch>');
    // Resume
    if(!$bad) {
        $modules .= '<b style="color:#08db08">Aucun code à risque trouvé</b><br/>';
    }
    if(!$bad1) {
        $dot_files .= '<b style="color:#08db08">Aucun fichier .xxx suspect trouvé</b><br/>';
    }
    if(!$bad2) {
        $htaccess_files .= '<b style="color:#08db08">Aucun fichier htaccess suspect trouvé</b><br/>';
    }    
    else {
        $htaccess_files .= '<b style="color:orange">Veuillez contrôler l\'ajout de fichier php dans ces répertoires (Normalement détectés dans la recherche des modules)</b><br/>';
        if($bad3) {
            Tools::generateHtaccess();
            $htaccess_files .= '<b style="color:orange">Veuillez contrôler le contenu de votre fichier .htaccess principal à la racine de votre site.</b><br/>';
        }
    }
    if(!$found_admin) {
        $admin_files .= '<b style="color:#08db08">Les fichiers de votre répertoire /admin sont conformes à la version d\'origine</b><br/>';
    }
    if(!$error_js) {
        $scripts .= '<b style="color:#08db08">Aucun fichier suspect JS detecté</b><br/>';
    }
    if(!$bad_image) {
        $images .= '<b style="color:#08db08">Aucun fichier image suspect detecté</b><br/>';
        if($limited) {
            $images .= '<span style="color:orange">Au vu des limitations de votre serveur l\'analyse des images produits n\'a pas été effectuée.</span><br/>';
        }
    }
    if(!$bad_infected) {
        $infects .= '<b style="color:#08db08">Aucun code suspect connu trouvé</b><br/>';
    }
    if(!$bad_found) {
        $bad_added_files .= '<b style="color:#08db08">Aucun fichier indésirable connu trouvé</b><br/>';
    }
    if(!$bad_heuristic) {
        $heuristic .= '<b style="color:#08db08">Aucun fichier sensible modifié</b><br/>';
    }
    $modules .= '<br><b style="color:#eee">Peak usage: '.round(memory_get_peak_usage()/ 1024).' KB of memory.</b><br>';
    $html .= $admin_files;
    $html .= $heuristic;
    $html .= $core_control;
    $html .= $scripts;
    $html .= $images;
    $html .= $infects;
    $html .= $bad_added_files;
    if(!$md5_list) {
        $html .= '<b style="color:red">Votre version '.(version_compare(_PS_VERSION_, '1.6.1.26', '>') && version_compare(_PS_VERSION_, '8.0.0.0', '<') ? 'PhenixSuite' : 'Prestashop').' est inexistante dans les archives md5, le contrôle d\'intégrité n\'a donc pu être effectué.</b><br/>';
    }
    $html .= $dot_files;
    $html .= $htaccess_files;
    if($indexes) {
        $html .= '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Recherche des index.php ajoutés:</h4>'.$indexes;
    }
    if($not_exist) {
        $html .= '<br/><h4 style="font-size: 1.1em;margin-bottom: 0.3em;margin-top: 0.3;color:white;">Recherche de fichiers php ajoutés:</h4>'.$not_exist;
    }
    $html .= $modules;
    $html .= PHP_EOL;
    $html .= '<h4 style="font-size:1.1em;margin-bottom:0.3em;margin-top:0.3;color:'.(!$integrity ? '#ff5900;' : 'white;').'">ANALYSE TERMINÉE <span style="font-size:13px"> (Effectuée en '.round(microtime(true) - $start_time, 3).' sec.)</span></h4>';

    if($integrity) {
        $html .= '<br/><b style="color:#08db08">Intégrité '.(version_compare(_PS_VERSION_, '1.6.1.26', '>=') && version_compare(_PS_VERSION_, '1.7.0.0', '<') ? 'PhenixSuite' : 'Prestashop').' OK: Tous vos fichiers coeur sont conformes ou patchés</b> <img width="50" src="https://devcustom.net/public/img/congrat.gif" />';
    }
    else {
        if($force) {
            if(version_compare(_PS_VERSION_, '1.7.0.0', '<=')) {
                $cache_folder_compile = $root_path.'cache/smarty/compile';
                $cache_folder_cache = $root_path.'cache/smarty/cache';
                if(file_exists($cache_folder_compile)) {
                    recursiveDeleteDirAndFiles($cache_folder_compile);
                }
                /* Some hostings as OVH cloud crashes
                if(file_exists($cache_folder_cache)) {
                    recursiveDeleteDirAndFiles($cache_folder_cache);
                }*/
            }
            else {
                $cache_folder_dev = $root_path.'var/cache/dev';
                $cache_folder_prod = $root_path.'var/cache/prod';
                if(file_exists($cache_folder_dev)) {
                    recursiveDeleteDirAndFiles($cache_folder_dev);
                }
                if(file_exists($cache_folder_prod)) {
                    recursiveDeleteDirAndFiles($cache_folder_prod);
                }
                $cache_folder_dev = $root_path.'app/cache/dev';
                $cache_folder_prod = $root_path.'app/cache/prod';
                if(file_exists($cache_folder_dev)) {
                    recursiveDeleteDirAndFiles($cache_folder_dev);
                }
                if(file_exists($cache_folder_prod)) {
                    recursiveDeleteDirAndFiles($cache_folder_prod);
                }
            }
            if(file_exists($root_path.'cache/class_index.php')) {
                @unlink($root_path.'cache/class_index.php');
            }
            if(file_exists($root_path.'cache/classes/index.php')) {
                @unlink($root_path.'cache/classes/index.php');
            }
            $html .= '<br/><br/><b style="color:#08db08">Nettoyage du cache '.(version_compare(_PS_VERSION_, '1.6.1.26', '>') && version_compare(_PS_VERSION_, '1.7.0.0', '<') ? 'PhenixSuite' : 'Prestashop').' effectué</b><br/>';
        }
        if($md5_list) {
            $html .= '<br/><b style="color:orange">!!! ATTENTION !!! Certains de vos fichiers coeurs ont été modifiés.<br/>Si ces modifications ne sont pas volontaires, nous vous conseillons de comparer les fichiers avec les 2 zips (suspicious_xxxx et '.(version_compare(_PS_VERSION_, '1.6.1.26', '>=') && version_compare(_PS_VERSION_, '1.7.0.0', '<') ? 'PhenixSuite' : 'Prestashop').') et de les restaurer dans leur version d\'origine si nécessaire.</b><br/><br/><b style="color:red">IMPORTANT: Si les fichiers coeurs modifiés commencent par /**/ vous avez été victime d\'un hack. Restaurez les versions d\'origine immédiatement !</b><br/>';
        }
    }
    $html .= PHP_EOL.PHP_EOL;
    if(version_compare(_PS_VERSION_, '1.6.1.26', '<=') || version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
        // https://www.prestashop.com/download/old/prestashop_'._PS_VERSION_.'.zip
        $html .= '<a href="https://github.com/PrestaShop/PrestaShop/releases/download/'._PS_VERSION_.'/prestashop_'._PS_VERSION_.'.zip"><button style="cursor:pointer;text-decoration:none;">Télécharger l\'archive de votre version d\'origine Prestashop '._PS_VERSION_.'</button></a>'.PHP_EOL.PHP_EOL;
        $html .= 'Si le lien ne fonctionne plus (Prestashop ayant modifié ses urls récemment) vous pouvez retrouver votre version ici: https://www.johanncorbel.fr/versions-de-prestashop/'.PHP_EOL.PHP_EOL;
    }
    else {
        $html .= '<a href="https://devcustom.net/public/scripts/prestashop_dl/prestashop_'._PS_VERSION_.'.zip"><button style="cursor:pointer;text-decoration:none;">Télécharger l\'archive de votre version d\'origine '.(version_compare(_PS_VERSION_, '1.6.1.26', '>=') && version_compare(_PS_VERSION_, '1.7.0.0', '<') ? 'PhenixSuite' : 'Prestashop').' '._PS_VERSION_.'</button></a>'.PHP_EOL.PHP_EOL;
    }
    if($zip_ok) {
        if(file_exists($zip_filename)) {
            $html .= '<a href="'.$zip_url.'"><button style="cursor:pointer;background: orange;border: 2px solid orange;border-style: outset;border-radius: 2px;text-decoration:none;">Télécharger l\'archive des fichiers à contrôler</button></a>'.PHP_EOL.PHP_EOL;
        }
        if(!file_exists($daily_zip)) {
            copy($zip_filename, $daily_zip);
        }
    }
    if(version_compare(_PS_VERSION_, '1.6.0.0', '>=') && version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
        $md5_filemanager = false;
        $api_url = 'https://devcustom.net/public/scripts/filemanager_md5.json';
        $md5_file = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
        if($md5_file) {
            $md5_filemanager = json_decode($md5_file);
        }
        if(!is_object($md5_filemanager)) {
            $md5_filemanager = false;
        }
        $found = 0;
        $link = $root_directory.'/**admin**/filemanager';
        $files1 = glob($root_path.$admin_dir .'/filemanager/*.php');
        $files2 = glob($root_path.$admin_dir .'/filemanager/*/*.php');
        $files = array_merge($files1, $files2);
        foreach($files as $file) {
            $script_name = str_replace($root_path.$admin_dir, 'admin', $file);
            if(basename($script_name) != 'index.php' && !empty($md5_filemanager->{$script_name}) && $md5_filemanager->{$script_name} != md5_file($file)) {
                $found++;
                $html .= '<b style="color:orange">>>> FILEMANAGER WARNING <<< : Fichier non patché '.$link .'</b><br/>';
                break;
            }
        }
        if($found) {
            $html .= PHP_EOL.'<b style="color:orange">Votre version possède une faille potentielle dans le répertoire '.$link.'. Veuillez télécharger la version patchée et la dézipper dans le répertoire de votre admin</b>'.PHP_EOL.PHP_EOL;
            $html .= '<a href="https://devcustom.net/public/scripts/filemanager.zip"><button style="cursor:pointer">Télécharger la version protégée du répertoire '.$link.'</button></a>'.PHP_EOL;
            $html .= '<b style="color:orange">ou</b>'.PHP_EOL;
            $html .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="POST"><input type="submit" name="update_filemanager" value="Patcher le répertoire" style="cursor:pointer"/></form>'. PHP_EOL;
        }
        else {
            $html .= '<b style="color:#08db08">INFO: Votre site possède la version patchée du répertoire '.$link.'</b><br/><br/>';
        }
    }
    if(version_compare(_PS_VERSION_, '1.7.0.0', '<') && version_compare(_PS_VERSION_, '1.6.1.27', '<')) {
        $html .= '<p style="text-align:center">La dernière version PhenixSuite à jour compatible PHP8 pour votre version 1.6 est disponible !<br/>';
        $html .= '<a href="https://eoliashop.com/prestashop-new"><button style="cursor:pointer;color:white;padding: 7px 9px;background-color: #268CCD !important;border-radius: 5px;">En savoir plus</button></a></p><br/>';
    }
    $html .= '<a href="https://eoliashop.com/" style="color: #6464f2;float:right;padding: 5px;text-decoration:none;" target="_blank">EoliaShop &copy;</a>';
    $message = preg_replace('~<div class="slide-out">(.*?)</div>~s', '', $html);
    $message = str_replace(' <button onclick="toggleSideNav(this)">Voir</button>', '', $message);
    $message .= '<br/><h3 style="font-size: 1.2em;padding:20px 5px 30px;color:white;">Ce script a été exécuté aujourd\'hui à '.date('H:i:s').' depuis '.$current_url.'<br/>IP de l\'apellant: '.getIpAddress().'<br/>Si vous n\'êtes pas à l\'origine de cette action, veuillez contrôler vos accès FTP ou vérifier ceux qui en possèdent.<br/>En cas de doute vous pouvez me contacter par mail à eolia@eoliashop.com</h3></font></pre></body></html>';
    $message = str_replace('background:black;padding:15px 15px 30px;color:white;', 'max-width:1200px;width:100%;word-wrap: break-word;background:black;padding:15px 15px 30px;color:white;', $message);
    $to      = '=?UTF-8?B?' . base64_encode($admin['firstname'].' '.$admin['lastname']).'?= <'.$admin['email'].'>';
    $subject = '=?UTF-8?B?' . base64_encode('INFO: Une analyse de votre site '.$_SERVER['HTTP_HOST'].' a été lancée depuis le script @Eolia') .'?=';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/html; charset=utf-8';
    $headers[] = 'From: Security scan <'.$encoded.'@'.$_SERVER['HTTP_HOST'].'>';
    $headers[] = 'Content-Transfer-Encoding: base64';
    mail($to, $subject, base64_encode($message), implode("\r\n", $headers));
    $html .= '<a href="'.$_SERVER['REQUEST_URI'].'"><button style="cursor:pointer;text-decoration:none;">Relancer le script</button></a>';
    if($escaped) {
        $html .= '<p style="margin-top: 10px;margin-bottom: -1em;color: darkviolet;">Fichiers de taille supérieure à 450Ko exclus de l\'analyse pour éviter le crash du script en défaut mémoire (segmentation fault):<br/>'.$escaped.'<br/>Si vous pensez que votre serveur peut les analyser, cliquez ci-dessous</p>
        <form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="display: table;">
            <input type="hidden" name="full" value="1">
            <input type="submit" value="Relancer l\'analyse complète de TOUS les fichiers" />
        </form>';
    }
    $html .= '
        </font></pre>
            <script text="javascript">
                function isHidden(el) {
                    return ((window.getComputedStyle(el).getPropertyValue("display") === "none") || (window.getComputedStyle(el).getPropertyValue("visibility") === "hidden"))
                }
                function toggleSideNav(el) {
                    var nextDiv = el.nextSibling;
                    if(isHidden(nextDiv)) {
                        var detailBlocks = document.getElementsByClassName("slide-out");
                        Array.prototype.forEach.call(detailBlocks, function(detailBlock) {
                            detailBlock.style.cssText = "display:none";
                            detailBlock.previousSibling.innerText = "Voir";
                        });
                        nextDiv.style.cssText = "display:block";
                        el.innerText = "Cacher";
                        el.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });
                    }
                    else {
                        nextDiv.style.cssText = "display:none";
                        el.innerText = "Voir";
                    }
                }
            </script></body></html>';
    die($html);
}
function displayFileError($color, $message, $file, $content = false) 
{
    $script_name = str_replace('./', '', $file);
    return '<div style="position:relative"><b style="color:'.$color.'">'.htmlspecialchars($message).' => '.htmlspecialchars($root_directory.'/'.$script_name).
    ($content ? '</b> <button onclick="toggleSideNav(this)">Voir</button><div class="slide-out">'.htmlspecialchars($content).'</div>' : '').'</div>';
}
function cleanIndex($content) 
{
    // Doekia
    $content = preg_replace('~/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/~m', '', $content);
    $content = preg_replace('~header\(.*\);~mU','',$content);
    $content = preg_replace('~<\?php~mU','',$content);
    $content = preg_replace('~exit+(|\(\));~mU','',$content);
    $content = preg_replace('~\?>~mU','',$content);
    $content = trim($content);
    return $content;
}
function getIpAddress() 
{
    $ipAddress = '';
    if(!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        return $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach($ipAddressList as $ip) {
            if(!empty($ip)) {
                $ipAddress = $ip;
                break;
            }
        }
    }
    else if(!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    }
    else if(!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    else if(!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    else if(!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    }
    else if(!empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}
function postmortem() 
{
   $resp = http_response_code();
   if(in_array($resp,array(500,504))) {
      file_put_contents(__DIR__.'/cleaner500.log', print_r(array(
          date('c'),
          '$_SERVER' => $_SERVER,
          'input' => file_get_contents('php://input'),
          error_get_last(),
      ), 1));
   }
}
function cleanDirectory($dir) 
{
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,
                 RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        }
        else {
            unlink($file->getRealPath());
        }
    }
}
function recursiveDeleteDirAndFiles($src, $all = true) 
{
    if($dir = opendir($src)) {
        while(false !== ($file = readdir($dir))) {
            if(($file != '.') && ($file != '..')) {
                if(is_dir($src.'/'.$file)) {
                    recursiveDeleteDirAndFiles($src.'/'.$file);
                }
                else {
                    unlink($src.'/'.$file);
                }
            }
        }
        closedir($dir);
        if($all) {
            rmdir($src);
        }
    }
}
function memoryTest() 
{
    $initial_memory = ini_get('memory_limit');
    if(ini_set('memory_limit', '-1') === false) {
        $memory_limit = $initial_memory;
    }
    else {
        $memory_limit = '-1';
    }
    $memory = array('memory_limit' => $memory_limit, 'display' => 'No limit');
    if(preg_match('/^(\d+)(.)$/i', $memory_limit, $matches)) {
        if($matches[2] == 'T') {
            $memory_limit = $matches[1]*(1024 ** 4);
        }
        elseif($matches[2] == 'G') {
            $memory_limit = $matches[1]*(1024 ** 3);
        }
        elseif($matches[2] == 'M') {
            $memory_limit = $matches[1]*(1024 ** 2);
        }
        elseif($matches[2] == 'K') {
            $memory_limit = $matches[1]*1024;
        }
        $memory['memory_limit'] = $memory_limit;
        $memory['display'] = $matches[1].$matches[2];
    }
    return $memory;
}
class DirFilter extends RecursiveFilterIterator 
{
    public function accept() {
        $excludes = array('cache', 'log', 'stats', 'error', 'upload', 'download', 'themes', 'docs', 'import', 'export', 'ps_checkout','psaddonsconnect', 'bin', 'var', 'vendor', 'p');
        return !(!$this->isDot() && $this->isDir() && in_array($this->getFilename(), $excludes));
    }
}
/* Version téléchargée depuis devcustom.net - 2024-01-31 14:56:12*/