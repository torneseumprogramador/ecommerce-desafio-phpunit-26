/*!
* Start Bootstrap - Personal v1.0.1 (https://startbootstrap.com/template-overviews/personal)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-personal/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project


const preecheSelect = async (input) => {
    const url = `/clientes.json?nome=${input.value}`;
    const select = document.getElementById('clienteId');
    select.innerHTML = '<option>Carregando ...</option>';
    
    try {
      const response = await fetch(url);
      
      if (!response.ok) {
        throw new Error(`Erro ao buscar dados: ${response.statusText}`);
      }
      
      const data = await response.json();
      
      if(data.length == 0){
        select.innerHTML = '<option>[Selecione]</option>';
        return
      }

      // Limpa os options atuais do select
      select.innerHTML = '';
      
      // Preenche os options com os dados recebidos
      data.forEach((cliente) => {
        const option = document.createElement('option');
        option.value = cliente.id;
        option.textContent = cliente.nome;
        select.appendChild(option);
      });
      
      // Habilita o select
      select.removeAttribute('disabled');
    } catch (error) {
      console.error(`Erro: ${error.message}`);
    }
  };
  