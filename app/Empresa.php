<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ["cnpj", "razao_social", "nome_fantasia", "inscricao_estadual", "inscricao_municipal",
                           "segmento", "email", "telefone", "cep", "estado", "cidade", "bairro",
                           "logradouro", "numero", "complemento"];
    
    
    protected $guarded = ['id', 'created_at', 'update_at'];

    protected $table = 'empresas';
}
