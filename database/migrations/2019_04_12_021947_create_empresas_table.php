<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnpj', 14);
            $table->string('razao_social');
            $table->string('nome_fantasia');
            $table->string('cep', 8);
            $table->string('estado', 2);
            $table->string('cidade');
            $table->string('bairro');
            $table->string('logradouro');
            $table->integer('numero');
            $table->string('telefone', 11);
            $table->string('email');
            $table->string('complemento')->nullable();
            $table->string('segmento');
            $table->string('inscricao_municipal');
            $table->string('inscricao_estadual')->nullable();
            
            $table->unique('cnpj');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
