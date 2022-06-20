# Módulo Jn2_Base

Jn2_Base é um módulo magento 2 que contém funcionalidades e configurações que serão padrão para todas as 
lojas.

- Repositório: https://bitbucket.org/jn2/jn2_base

## Instalação

Use [Composer](https://getcomposer.org) para instalar esta biblioteca:
[`jn2/base`](https://bitbucket.org/jn2/jn2_base)

Execute o seguinte comando no diretório do projeto para adicionar a dependência:

```sh
composer require jn2/base "^1.0"
```

Como alternativa, adicione a dependência diretamente ao seu arquivo `composer.json`:

```json
"require": {
    "jn2/base": "^1.0"
}
```

#### Após a instalação, ative o módulo executando os seguintes comandos:

```sh
$ php bin/magento module:enable Jn2_Base --clear-static-content
$ php bin/magento setup:upgrade
```

## 3. Recursos:

### Bloco para informações do frete

Um bloco padrão para inserir informações sobre frete, ele vai ser exibido na pagina de seleção 
do método de entrega durante o checkout, se estiver ativado nas configurações.

Para ativar você deve entrar em `Lojas > Configuração > Vendas > Finalizar Compra > Opcões de Compra > Mostrar bloco de informações de frete no checkout`, você deve marcar como sim para o bloco ser exibido.

![shipping_inforamtion](https://i.imgur.com/cabGG8b.png) 

Para adicionar suas informações você deve editar o bloco(`Conteúdo > Blocos`) com o identificador `shipping_information`,
somente o nome e o conteúdo deste bloco pode ser alterado, ele não pode ser excluído.

Depois de alterar os dados e ativar o bloco nas configurações você deve limpar o cache para ele ser exibido sem erros.

### Link para imagem da categoria

Novo campo para as categorias que permite a inserção de um link para a imagem da
categoria se tornar clicável.

O campo fica dentro de uma categoria em `Catálogo > Categorias > Sua Categoria > Conteúdo > Link da Imagem da Categoria`.

### Configurações padrão

Quando o móduo é instalado algumas configurações recebem valores padrão como
`'general/locale/code' => 'pt_BR'` e `'customer/address/street_lines' => '4'`. 

### Injeção de bibliotecas

A biblioteca Jquery InputMask é inserida como padrão. 
