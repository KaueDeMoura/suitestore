let selectProduto = document.getElementById = ('selectProduto');
let InputTaxa = document.getElementById = ('Taxa');
let InputPreco = document.getElementById = ('Preco');

document.querySelector('.selectProduto').addEventListener('change', () => {

    var element = document.querySelector('.selectProduto');
    var valorSel = element.options[element.selectedIndex].value;

    fetch('../processos/insertPrecoHome.php?selectProduto=' + valorSel )
        .then(response => {
            return response.text();
        })
        .then(texto => {
            Preco.placeholder = texto;    
            Preco.value = texto;
        });
});

