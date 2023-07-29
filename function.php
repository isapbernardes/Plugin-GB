<?php


// Função para obter dados do repositório via API do GitHub
function plugin_obter_dados ($usuario, $repositorio){ 
   $url = "https://api.github.com/repos/$usuario/$repositorio";

   // incluir o User-Agent (obrigatório pela API do GitHub)

 $args = array (
    'headers' => array (
        'User-Agent' => 'Meu-Plugin GitHub'
    )
    );

    // Faz a requisição GET para a API 

    $response = wp_remote_get ($url, $args);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body ($response);
    $data = json_decode ($body, true);

    return $data;
    
}

//Função para exibir dados do repositório no frontend

function plugin_exibir_dados ($usuario, $repositorio) {
   $dados_repositorio = plugin_obter_dados($usuario, $repositorio);

   if ($dados_repositorio) {
    $numero_estrelas = $dados_repositorio ['stargazers_count'];
    $numero_forks = $dados_repositorio ['forks'];
     $link_repositorio = $dados_repositorio ['html_url'];

     echo '<p>Número de estrelas:' . $numero_estrelas . '<p>';
     echo '<p>Número de forks: ' . $numero_forks . '</p>';
     echo '<p>Link do repositório: <a href="' . $link_repositorio . '">' . $link_repositorio . '</a></p>';

   } else {
    echo 'Não foi obter os dados do repositório';
   }
}

//Função para registrar o shortcode

function plugin_registrar_shortcode () {
    add_shortcode('plugin_repositorio', 'plugin_shortcode');
}

//Callback

function plugin_shortcode ($atts){
    $atts = shortcode_atts (array(
        'usuario' => 'openai',
        'repositorio' => 'gpt-3.5'
    ), $atts);

    ob_start();
    plugin_exibir_dados($atts['usuario'], $atts['repositorio']);
    return ob_get_clean();
}



//Registrar o shortcode

add_action('init', 'plugin_registrar_shortcode');







