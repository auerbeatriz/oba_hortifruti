<?php

function listFornecedores($post) {
    $result = $post->getFornecedoresName();
    foreach ($result as $row) {
        echo "<option value=".$row['id'].">".$row['id']. " - " . utf8_encode($row["razao_social"])."</option>";
    }
}

function listClientes($post) {
    $result = $post->getClientsName();
    foreach ($result as $row) {
        echo "<option value=".$row['id'].">".$row['id']. " - " . utf8_encode($row["nome"])."</option>";
    }
}

function listProdutos($post) {
    $result = $post->getProductsName();
    foreach ($result as $row) {
        echo "<option value=".$row['id'].">".$row['id']. " - " . utf8_encode($row["nome"])."</option>";
    }
}

function listFornecedorData($fornecedor) {
    $razaoSocial = utf8_encode(strtoupper(filter_var($fornecedor["razao_social"], FILTER_SANITIZE_SPECIAL_CHARS)));
    $logradouro = utf8_encode(filter_var($fornecedor["logradouro"], FILTER_SANITIZE_SPECIAL_CHARS));
    $complemento = utf8_encode(filter_var($fornecedor["complemento"], FILTER_SANITIZE_SPECIAL_CHARS));
    $bairro = utf8_encode(filter_var($fornecedor["bairro"], FILTER_SANITIZE_SPECIAL_CHARS));
    $cidade = utf8_encode(filter_var($fornecedor["cidade"], FILTER_SANITIZE_SPECIAL_CHARS));
    $uf = strtoupper(filter_var($fornecedor["uf"],FILTER_SANITIZE_SPECIAL_CHARS));

    echo "<label><b>".$razaoSocial."</b></label><br>
    <label>".$logradouro.", ".$fornecedor["numero"].", ".$complemento." - ".$bairro." - ".$cidade." - ".$uf." - CEP: ".$fornecedor["cep"]."</label><br>
    <label>CNPJ: ".$fornecedor["cnpj"]."<label><br>
    <label>E-mail: ".utf8_encode($fornecedor["email"])."<label><br>
    <label>Telefone: ".$fornecedor["telefone"]."<label><hr>";
}

function listClienteData($cliente) {
    $nome = utf8_encode(strtoupper(filter_var($cliente["nome"], FILTER_SANITIZE_SPECIAL_CHARS)));
    $logradouro = utf8_encode(filter_var($cliente["logradouro"], FILTER_SANITIZE_SPECIAL_CHARS));
    $complemento = utf8_encode(filter_var($cliente["complemento"], FILTER_SANITIZE_SPECIAL_CHARS));
    $bairro = utf8_encode(filter_var($cliente["bairro"], FILTER_SANITIZE_SPECIAL_CHARS));
    $cidade = utf8_encode(filter_var($cliente["cidade"], FILTER_SANITIZE_SPECIAL_CHARS));
    $uf = strtoupper(filter_var($cliente["uf"],FILTER_SANITIZE_SPECIAL_CHARS));

    echo "<label><b>".$nome."</b></label><br>
    <label>".$logradouro.", ".$cliente["numero"].", ".$complemento." - ".$bairro." - ".$cidade." - ".$uf." - CEP: ".$cliente["cep"]."</label><br>
    <label>CPF: ".$cliente["cpf"]."<label><br>
    <label>E-mail: ".utf8_encode($cliente["email"])."<label><br>
    <label>Telefone: ".$cliente["telefone"]."<label><hr>";
}

function listProdutoData($produto) {
    $nome = utf8_encode(strtoupper(filter_var($produto["nome"], FILTER_SANITIZE_SPECIAL_CHARS)));
    $fornecedor = utf8_encode(filter_var($produto["fornecedor"], FILTER_SANITIZE_SPECIAL_CHARS));

    echo "<label><b>".$nome."</b></label><br>
    <label>Fornecedor: ".$fornecedor."</label><br>
    <label>Preço de venda: R$".$produto["preco_venda"]."<label>
    <figure>
        <img src='".$produto["foto"]."'>
    </figure><hr>";
}

function listRegistrosCaderneta($registro) {
    $cliente = utf8_encode(strtoupper(filter_var($registro["cliente"], FILTER_SANITIZE_SPECIAL_CHARS)));
    $produto = utf8_encode(filter_var($registro["produto"], FILTER_SANITIZE_SPECIAL_CHARS));

    echo "<label><b>".$cliente."</b></label><br>
    <label>".$registro["qtd"]." ".$produto."</label><br>
    <label>Total: R$".$registro["total"]."<label><hr>";
}

function limpaDocumento($documento) {
    //Primeiro retira os espaços do começo e do final.
   $documento = trim($documento);
   //Substitui o ponto por nada
    $documento = str_replace(".", "", $documento);
   //Troca o traço por nada
    $documento = str_replace("-", "", $documento);
   //Troca o espaço por nada
    $documento = str_replace(" ", "", $documento);
   //Troca a barra por nada
    $documento = str_replace("-", "", $documento);

    return $documento;
}

function validaCnpj($cnpj) {
    $cnpj = limpaDocumento($cnpj);
    if(strlen($cnpj) < 14) {
        return false;
    }

    $s1 = "543298765432";
    $s2 = "6543298765432";
    $validador = 0;
    $digitos = substr($cnpj, 0, 12);
    $verificadoresPassados = substr($cnpj, 12);

    //  fazendo a contagem do primeiro dígito
    for($i=0; $i < 12; $i++) {
        $validador += (int) substr($s1, $i, 1) * (int) substr($digitos, $i, 1);
    }
    if((int) ($validador % 11) < 2) {
        $digitos .= "0";
    } 
    else {
        $digitos .=  strval(11 - ($validador % 11));
    }

    //  fazendo a contagem do segundo dígito
    $validador = 0;
    for($i=0; $i < 13; $i++) {
        $validador += (int) substr($s2, $i, 1) * (int) substr($digitos, $i, 1);
    }
    if((int) ($validador % 11) < 2) {
        $digitos .= "0";
    } 
    else {
        $digitos .=  strval(11 - ($validador % 11));
    }

    //  comparando o dígito informado com o dígito calculado
    if(strcmp(substr($digitos, 12), $verificadoresPassados) == 0) {
        return true;
    }
    else {
        return false;
    }
}

function validaCpf($cpf) {
    $cpf = limpaDocumento($cpf);
    if(strlen($cpf) < 11) {
        return false;
    }

    $contador = 10;
    $validador = 0;
    $digitos = substr($cpf, 0, 9);
    $verificadoresPassados = substr($cpf, 9);

    //  fazendo a contagem do primeiro dígito
    for($i=0; $i < 9; $i++) {
        $validador += (int) substr($digitos, $i, 1) * $contador;
        $contador -= 1;
    }
    if((int) ($validador * 10 % 11) == 10) {
        $digitos .= "0";
    } 
    else {
        $digitos .=  strval((int) $validador * 10 % 11);
    }
 
    //  fazendo a contagem do segundo dígito
    $contador = 11;
    $validador = 0;
    for($i=0; $i < 13; $i++) {
        $validador += (int) substr($digitos, $i, 1) * $contador;
        $contador -= 1;
    }
    if((int) ($validador * 10 % 11) == 10) {
        $digitos .= "0";
    } 
    else {
        $digitos .=  strval((int) $validador * 10 % 11);
    }

    //  comparando o dígito informado com o dígito calculado
    if(strcmp(substr($digitos, 9), $verificadoresPassados) == 0) {
        return true;
    }
    else {
        return false;
    }
}

function exibeErros($erros) {
    if(!empty($erros)) {
        foreach($erros as $erro) {
            echo $erro;
        }
    }
}

?>