<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmpresaTest extends TestCase
{
    /**
     * Teste Api Route.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/api/empresa');

        $response->assertStatus(200);
    }

    /**
    *  Testa a inserção de empresas
    *
    *  @return void
    */
    public function testStore() {

        $response = $this->json('POST','/api/empresa');

        $response->assertStatus(422);

        $data = $this->getEmpresa();

        $response = $this->json('POST', '/api/empresa', $data);

        $response->assertStatus(200);

    } 

    /**
    *  Testa o update de empresas
    *
    *  @return void
    */
    public function testUpdate() {

        $response = $this->get('/api/empresa');

        $data = $response->getData();

        $data = $data->data;

        if(empty($data)) {
            $empresa = $this->getEmpresa();

            $response = $this->json('POST', '/api/empresa', $empresa);

            $response = $this->get('/api/empresa');

            $data = $response->getData();
    
            $data = $data->data[0];
        }
        else {
            $data = $data[0];
        }

        $data->telefone = '31975452165';

        $data = (array) $data;

        $id = $data["id"];

        $response = $this->json('PUT', '/api/empresa/'.$data["id"], $data);

        $response->assertStatus(200);

    }

    /**
    *  Testa a exclusão de empresas
    *
    *  @return void
    */
    public function testDestroy() {

        $response = $this->get('/api/empresa');

        $data = $response->getData();

        $data = $data->data;

        if(empty($data)) {
            $empresa = $this->getEmpresa();

            $response = $this->json('POST', '/api/empresa', $empresa);

            $response = $this->get('/api/empresa');

            $data = $response->getData();
    
            $data = $data->data[0];
        }
        else {
            $data = $data[0];
        }

        $data = (array) $data;

        $id = $data["id"];

        $response = $this->json('DELETE', '/api/empresa/'.$id);

        $response->assertStatus(200);
    }


    /**
    *  Testa validação de tamanho do cnpj, e-mail váido,  nome_fantasia,
    *  bairro, logradouro, numero, estado são required
    *
    *  @return void
    */
    public function testValidacoes() {

 
        $data = array("cnpj" => '11111', 'email' => '1.com');

        $response = $this->json('POST', '/api/empresa', $data);

        // Valida cnpj com 14 caracteres
        $response->assertJsonFragment(["cnpj" => array("The cnpj must be at least 14 characters.")]);
        // Valida e-mail válido
        $response->assertJsonFragment(["email" => array("The email must be a valid email address.")]);
        // Valida nome fantasia required
        $response->assertJsonFragment(["nome_fantasia" => array("The nome fantasia field is required.")]);
        // Valida bairro required
        $response->assertJsonFragment(["bairro" => array("The bairro field is required.")]);
        // Valida logradouro required
        $response->assertJsonFragment(["logradouro" => array("The logradouro field is required.")]);
        // Valida numero required
        $response->assertJsonFragment(["numero" => array("The numero field is required.")]);
        // Valida estado required
        $response->assertJsonFragment(["estado" => array("The estado field is required.")]);
    }

    public function getEmpresa() {
        
        return array( 
            "bairro" => "Santa Branca",
            "cep" => "31565170",
            "cidade" => "Belo Horizonte",
            "cnpj" => "16742447000176",
            "cnpj_mask" => "16.742.44/7000-17",
            "complemento" => null,
            "created_at" => "2019-04-16 03:35:50",
            "email" => "csb@gmail.com",
            "estado" => "MG",
            "inscricao_estadual" => "11111",
            "inscricao_municipal" => "11111",
            "logradouro" => "Rua São João da Lagoa",
            "nome_fantasia" => "COLEGIO SANTA BRANCA",
            "numero" => 380,
            "razao_social" => "COLEGIO SANTA BRANCA LTDA",
            "segmento" => "Produto",
            "telefone" => "11111111111",
            "telefone_mask" => "(11) 11111-1111"
        );
    }
}
