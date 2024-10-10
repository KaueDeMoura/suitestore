let selectProdutos = document.getElementById = ('selectProduto');
let InputTaxas = document.getElementById = ('Taxa');
let InputPrecos = document.getElementById = ('Preco');

document.querySelector('.selectProduto').addEventListener('change', () => {
    let selectProduto = document.getElementById = ('selectProduto');

    var element = document.querySelector('.selectProduto');
    var valorSel = element.options[element.selectedIndex].value;




    fetch('../processos/insertTaxaHome.php?selectProduto=' + valorSel )
        .then(response => {
            return response.text();
        })
        .then(texto => {
            Taxa.placeholder = texto; 
            Taxa.value = texto;    
        });
  });
