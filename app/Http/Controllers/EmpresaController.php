<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;


class EmpresaController extends Controller
{
    /*
    *  Retorna todas as empresas
    **/

    public function index(Request $request) {

        $empresas = Empresa::paginate(3);

        $mask = "##.###.##/####-##";
        $telefoneMask10 = "(##) ####-####";
        $telefoneMask11 = "(##) #####-####";

        foreach($empresas as $empresa) {

            $empresa->cnpj_mask = $this->mask($empresa->cnpj, $mask);
            $length = strlen($empresa->telefone);
            if($length == 10) {
                $empresa->telefone_mask = $this->mask($empresa->telefone, $telefoneMask10);
            }
            else {
                $empresa->telefone_mask = $this->mask($empresa->telefone, $telefoneMask11);
            }
        }

        return $empresas;
    }

    /*
    *  Salva uma nova empresa
    **/
    public function store(Request $request) {

        $request->validate([
            'cnpj'                           => 'required|string|unique:empresas|min:14|max:14',
            'nome_fantasia'                  => 'required|string|max:255',
            'razao_social'                   => 'required|string|max:255',
            'email'                          => 'required|string|email|max:255',
            'inscricao_municipal'            => 'required|max:255',
            'segmento'                       => 'required|string',
            'telefone'                       => 'required|min:10|max:11',
            'cep'                            => 'required|min:8|max:8',
            'estado'                         => 'required|min:2|max:2',
            'cidade'                         => 'required|string|max:255',
            'bairro'                         => 'required|string|max:255',
            'logradouro'                     => 'required|string|max:255',
            'numero'                         => 'required|max:5'
        ]);

        $empresa = new Empresa($request->all());
        $empresa->save();
    }

    /*
    *   Atualiza os dados de uma empresa 
    **/
    public function update(Request $request, $empresaId) {

        $request->validate([
            'cnpj'                           => 'required|string|min:14|max:14',
            'nome_fantasia'                  => 'required|string|max:255',
            'razao_social'                   => 'required|string|max:255',
            'email'                          => 'required|string|email|max:255',
            'inscricao_municipal'            => 'required|max:255',
            'segmento'                       => 'required|string',
            'telefone'                       => 'required|min:10|max:11',
            'cep'                            => 'required|min:8|max:8',
            'estado'                         => 'required|min:2|max:2',
            'cidade'                         => 'required|string|max:255',
            'bairro'                         => 'required|string|max:255',
            'logradouro'                     => 'required|string|max:255',
            'numero'                         => 'required|max:5'
        ]);

        $empresa = Empresa::findOrFail($empresaId);

        $empresa->fill($request->all())->save();

        return $empresa;
    }

    /*
    *   Deleta uma empresa
    **/
    public function destroy($empresaId) {
        Empresa::where('id', $empresaId)->delete();
    }

    /*
    *   Aplica mascara
    **/
    public function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset ($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset ($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
    
}
