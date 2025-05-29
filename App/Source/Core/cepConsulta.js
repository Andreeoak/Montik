// cepConsulta.js
document.addEventListener('DOMContentLoaded', function () {
  const btnConsultarCep = document.getElementById('btnConsultarCep');
  const inputCep = document.getElementById('cepInput');
  const resultadoCep = document.getElementById('resultadoCep');

  if (!btnConsultarCep || !inputCep || !resultadoCep) return;

  btnConsultarCep.addEventListener('click', function () {
    const cep = inputCep.value.trim();

    if (!/^\d{8}$/.test(cep)) {
      resultadoCep.textContent = 'Por favor, insira um CEP válido com 8 números.';
      return;
    }

    resultadoCep.textContent = 'Consultando CEP...';

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(response => response.json())
      .then(data => {
        if (data.erro) {
          resultadoCep.textContent = 'CEP não encontrado.';
        } else {
          const endereco = `${data.logradouro || ''}, ${data.bairro || ''}, ${data.localidade || ''} - ${data.uf || ''}`;
          resultadoCep.textContent = `Endereço encontrado: ${endereco}`;
        }
      })
      .catch(() => {
        resultadoCep.textContent = 'Erro ao consultar o CEP. Tente novamente.';
      });
  });
});