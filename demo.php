<?php
include_once'SimpleHTMLDOM/simple_html_dom.php';
include_once 'SiteManipulation/ManipulationWebSite.php';

use SiteManipulation\ManipulationWebSite as ManipulationSite;

echo '<small>Este projeto utiliza o seguinte HTML parser: <a href="http://simplehtmldom.sourceforge.net/" target=_blank>PHP Simple HTML DOM Parser</a></small>';

echo '<h2>Mansite Demo:</h2><h3>Instanciando o objeto:</h3>';
highlight_string('
    <?php
        $manipulator = new Manipulation($url); //$url o(a) site(pagina) a ser manipulado
    ');
$manipulator = new ManipulationSite('http://www.google.com');


echo '<h3>Pegar todos os links (a href) da página:</h3>';
highlight_string('
    <?php
        $urls = $manipulator->getAllLinks();
        //$urls = $manipulator->getAllLinks(true); 
        echo \'<pre>\';
            var_dump($urls);
        echo \'</pre>\';
    ');
echo '<br />Resultados:<br />';
$urls = $manipulator->getAllLinks();
echo '<pre>';
var_dump($urls);
echo '</pre>';


echo '<br /><h3>Pegar o último link encontrado na página:</h3>';
highlight_string('
    <?php
        $manipulator = new Manipulation(\'http://www.google.com\');
        echo $manipulator->getLastAnchor(true); //true ou 1 para adicionar a URL usada no metodo construtor
    ');
echo '<br />Resultados:<br />';
echo $manipulator->getLastAnchor(true);


echo '<br /><br /><br /><h3>Pegar o primeiro link encontrado na página:</h3>';
highlight_string('
    <?php
        echo $manipulator->getFirstAnchor();
    ');
echo '<br />Resultados:<br />';
echo $manipulator->getFirstAnchor();


echo '<br /><br /><br /><h3>Pegar um link especificando a ordem em que se encontra:</h3>';
highlight_string('
    <?php
        echo $manipulator->getAnAnchor(2); //terceiro link do site (inicia em zero)
    ');
echo '<br />Resultados:<br />';
echo $manipulator->getAnAnchor(2);


echo '<br /><br /><br /><h3>Pegar todas as imagens (img src) da página (em array):</h3>';
highlight_string('
    <?php
        $images = array();
        $images = $manipulator->getAllImages();
        echo \'<pre>\';
            var_dump($images);
        echo \'</pre>\';    
    ');
echo '<br />Resultados:<br />';
$images = array();
$images = $manipulator->getAllImages();
echo '<pre>';
var_dump($images);
echo '</pre>';


echo '<br /><h3>Pegar conteúdo do site em string (existem opções para retornar em array):</h3>';
highlight_string('
    <?php
        $manipulator->showPageLines();
        //$manipulator->showPageLines(true); //se o primeiro parametro = true ou 1, numera as linhas encontradas
    ');
echo '<br />Resultados:<br />';
echo '<font size="2">';
echo htmlspecialchars('<!doctype html><html itemscope="" itemtype="http://schema.org/WebPage"><head><meta itemprop="image" content="/images/google_favicon_128.png"><title>Google</title><script>(function(){
window.google={kEI:"i0pAUpyHOIPs8wTah4HYAg",getEI:function(a){for(var b;a&&(!a.getAttribute||!(b=a.getAttribute("eid")));)a=a.parentNode;return b||google.kEI},https:function() ..........');
echo '</font><br />';


echo '<br /><br /><h3>Pegar todo o conteúdo da página para exibi-la novamente:</h3>';
highlight_string('
    <?php
        $manipulator->getContentPage();
    ');


echo '<br /><br /><h3>Mostrar conteúdo da página sem tags HTML:</h3>';
highlight_string('
    <?php
        $manipulator->getOnlyText();
    ');
echo '<br />Resultados:<br />';
echo $manipulator->getOnlyText();


echo '<br /><br /><h3>Outros...</h3>';
highlight_string('
    <?php
		$text = array();
        $text = $manipulator->getContentToArray(); //retorna um array com uma linha da pagina em cada chave do array
		//principalmente util em arquivos de texto: http://www.example.com/file.txt
		
		$line = $manipulator->getRow($numberLine); //retorna uma linha especifica do site
    ');