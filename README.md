## Manipsites

Uma pequena ferramenta que estende o [PHP Simple HTML DOM Parser](http://simplehtmldom.sourceforge.net/) para facilitar ainda mais a manipulação de sites utilizando o poder do cURL com o parser para HTML. A página demo.php contém detalhes dos exemplos. Maiores informações leia os comentários dos métodos.

A small tool that extends the [PHP Simple HTML DOM Parser](http://simplehtmldom.sourceforge.net/) to further facilitate the manipulation of sites using the power of cURL with the parser for HTML. The demo.php page contains details of the examples. For english, see the documentation of methods.


### Pré-requisitos:

- PHP 5.3;
- Configurar no php.ini -> allow\_url_fopen = On
- cURL ativado (para funcionar atrás de um proxy);


### Alguns exemplos:

    $manipulator = new Manipulation($url); //$url o(a) site(pagina) a ser manipulado
    
    $urls = array();
    $urls = $manipulator->getAllLinks(); //retorna todos os href da página
    
    echo $manipulator->getLastAnchor; //retorna o ultimo link encontrado na pagina
    //outros metodos sao: getFirstAnchor(), getAnAnchor($number)

    echo $manipulator->getLastAnchor(true); //variante do exemplo anterior, adiciona $url como sufixo para o link    
    
    $images = array();
    $images = $manipulator->getAllImages(); //retorna todos os src de imagens da pagina

    $textOnly = $manipulator->getOnlyText(); //captura a página e retira as tags HTML

    $manipulator->getContentPage(); //mostra o conteudo da pagina designada por $url
    
    $manipulator->showPageLines(true); //mostrar conteudo da pagina linha a linha, true para enumerar as linhas
    
    $text = array();
    $text = $manipulator->getContentToArray(); //retorna um array com uma linha da pagina em cada chave do array
    //principalmente util em arquivos de texto: http://www.example.com/file.txt
    
    $line = $manipulator->getRow($numberLine); //retorna uma linha especifica do site
    
    //para utilizar a classe atras de um proxy, basta setar o endereco e porta:
    $manipulator->setProxyAddress('addressproxy');
    $manipulator->setProxyPort('port'); //3128 por exemplo
    

