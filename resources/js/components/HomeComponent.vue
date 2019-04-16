<template>
   <div class="container"> 
       <div class="card text-center">
            <div class="card-header">
                Teste de Desenvolvimento Eduxe
            </div>
            <div class="card-body">
                <h5 class="card-title">Autor: Pedro Moutinho</h5>
                <p class="card-text">
                    <b>Teste de desenvolvimento realizado por Pedro Moutinho.</b>
                </p>
                <p class="card-text">
                    <a href="https://1drv.ms/b/s!AqA9dfx4VWHUgdl-7NQhOBNx1myaxA" target="_blank">Especificação</a>
                </p>
                <p class="card-text">
                    <a href="https://github.com/pepedoni/eduxe" target="_blank">Repositório no Github</a>
                </p>
                <router-link :to="{ name: 'empresa' }" class="btn btn-primary">Ir para o Desafio</router-link>
            </div>
        </div>
   </div>
  
</template>