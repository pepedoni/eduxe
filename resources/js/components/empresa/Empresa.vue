<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Empresa</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn color="info" dark class="mb-2" @click="getDataFromApi">Recarregar</v-btn>
      <v-btn color="info" dark class="mb-2" @click="add">Adicionar Empresa</v-btn>
    </v-toolbar>

    <v-dialog v-model="dialog" width="600">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-alert
            :value="successNotification"
            class="alert"
            color="white"
            icon="check_circle"
            outline
          >Empresa salva com sucesso.</v-alert>
          <v-alert
            :value="errorNotification"
            color="error"
            icon="warning"
            outline
          >{{ this.errorMessage }}</v-alert>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md6>
                  <v-text-field
                    v-model="editedEmpresa.cnpj"
                    mask="##.###.###/####-##"
                    required
                    :disabled="this.mode == 'EDIT' || this.mode == 'VIEW'"
                    label="CNPJ *"
                    :error-messages="errors.collect('cnpj')"
                    v-validate="'required|max:14'"
                    data-vv-name="cnpj"
                    @input="cnpjChange"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-text-field
                    required
                    label="Razão Social *"
                    v-model="editedEmpresa.razao_social"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('razao_social')"
                    v-validate="'required'"
                    data-vv-name="razao_social"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    v-model="editedEmpresa.nome_fantasia"
                    label="Nome Fantasia *"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('nome_fantasia')"
                    v-validate="'required'"
                    data-vv-name="nome_fantasia"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    label="Inscrição Estadual"
                    v-model="editedEmpresa.inscricao_estadual"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('inscricao_estadual')"
                    v-validate="{ required: this.editedEmpresa.segmento != 'Serviços' }"
                    data-vv-name="inscricao_estadual"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    required
                    label="Inscrição Municipal *"
                    v-model="editedEmpresa.inscricao_municipal"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('inscricao_municipal')"
                    v-validate="'required'"
                    data-vv-name="inscricao_municipal"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm3 md3>
                  <v-select
                    v-model="editedEmpresa.segmento"
                    label="Segmento *"
                    :disabled="this.mode == 'VIEW'"
                    :items="segmentos"
                    :error-messages="errors.collect('segmento')"
                    v-validate="'required'"
                    data-vv-name="segmento"
                  ></v-select>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-text-field
                    v-model="editedEmpresa.email"
                    :rules="[rules.required, rules.email]"
                    label="Email *"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('email')"
                    v-validate="'required|email'"
                    data-vv-name="email"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm3 md3>
                  <v-text-field
                    v-model="editedEmpresa.telefone"
                    mask="(##) #########"
                    label="Telefone *"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('telefone')"
                    v-validate="'required|min:10|max:11'"
                    data-vv-name="telefone"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    v-model="editedEmpresa.cep"
                    mask="#####-###"
                    label="CEP *"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('cep')"
                    v-validate="'required|min:8|max:8'"
                    data-vv-name="cep"
                    @input="cepChange"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-select
                    v-model="editedEmpresa.estado"
                    label="Estado *"
                    :disabled="this.mode == 'VIEW'"
                    :items="estados"
                    :error-messages="errors.collect('estado')"
                    v-validate="'required'"
                    data-vv-name="estado"
                  ></v-select>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    v-model="editedEmpresa.cidade"
                    label="Cidade *"
                    :disabled="this.mode == 'VIEW'"
                    :error-messages="errors.collect('cidade')"
                    v-validate="'required'"
                    data-vv-name="cidade"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    required
                    label="Bairro *"
                    :disabled="this.mode == 'VIEW'"
                    v-model="editedEmpresa.bairro"
                    :error-messages="errors.collect('bairro')"
                    v-validate="'required'"
                    data-vv-name="bairro"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm5 md5>
                  <v-text-field
                    required
                    label="Logradouro *"
                    :disabled="this.mode == 'VIEW'"
                    v-model="editedEmpresa.logradouro"
                    :error-messages="errors.collect('logradouro')"
                    v-validate="'required'"
                    data-vv-name="logradouro"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm3 md3>
                  <v-text-field
                    required
                    label="Número *"
                    :disabled="this.mode == 'VIEW'"
                    v-model="editedEmpresa.numero"
                    :error-messages="errors.collect('numero')"
                    v-validate="'required'"
                    data-vv-name="numero"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-text-field
                    required
                    label="Complemento"
                    :disabled="this.mode == 'VIEW'"
                    v-model="editedEmpresa.complemento"
                    :error-messages="errors.collect('complemento')"
                    data-vv-name="complemento"
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="blue darken-1"
              flat
              @click="close"
            >{{ mode == 'VIEW' ? 'Voltar' : 'Cancelar'}}</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              class="right"
              color="blue darken-1"
              flat
              type="submit"
              align="right"
              v-if="mode == 'EDIT'  || mode == 'ADD'"
              @click="save"
            >Salvar</v-btn>
          </v-card-actions>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-data-table
      :headers="headers"
      :loading="loading"
      :items="empresas"
      :pagination.sync="pagination"
      :total-items="totalEmpresas"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.cnpj_mask }}</td>
        <td>{{ props.item.razao_social }}</td>
        <td>{{ props.item.email }}</td>
        <td>{{ props.item.telefone_mask }}</td>

        <td class="justify-center layout px-0">
          <v-icon small class="mr-2" @click="editEmpresa(props.item)">edit</v-icon>
          <v-icon small class="mr-2" @click="deleteEmpresa(props.item)">delete</v-icon>
          <v-icon small @click="viewEmpresa(props.item)">info</v-icon>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<style>
.alert {
  background-color: white;
  color: #2fb399 !important;
  z-index: 100;
  top: 50%;
}

.error--text {
  color: red !important;
}

.right {
  float: right;
}

.v-card__text {
  padding: 0px !important;
}
</style>

<script>
import { required, maxLength, email } from "vuelidate/lib/validators";

export default {
  $_veeValidate: {
    validator: "new"
  },

  data() {
    return {
      headers: [
        {
          text: "CNPJ",
          align: "left",
          sortable: true,
          value: "name"
        },
        { text: "Razão Social", sortable: true, value: "razao_social" },
        { text: "Email", value: "email" },
        { text: "Telefone", value: "telefone" },
        { text: "Ações", value: "name", sortable: false, align: "center" }
      ],
      disabled: [],
      estados: [
        "AC",
        "AL",
        "AP",
        "AM",
        "BA",
        "CE",
        "DF",
        "ES",
        "GO",
        "MA",
        "MT",
        "MS",
        "MG",
        "PA",
        "PB",
        "PR",
        "PE",
        "PI",
        "RJ",
        "RN",
        "RS",
        "RO",
        "RR",
        "SC",
        "SP",
        "SE",
        "TO"
      ],
      segmentos: ["Produto", "Serviços", "Produtos e Serviços"],
      endPoint: "http://127.0.0.1:8000/api/empresa",
      editedEmpresa: {},
      editedIndex: -1,
      pagination: {
        pagination: 1,
        rowsPerPage: 1,
        totalEmpresas: 1
      },
      rules: {
        required: value => !!value || "Required.",
        counter: value => value.length <= 20 || "Max 20 characters",
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "Invalid e-mail.";
        }
      },
      totalEmpresas: 10,
      mode: "ADD",
      dialog: false,
      loading: true,
      empresas: [],
      errorNotification: false,
      errorMessage: 'Ocorreram erros ao salvar os dados da empresa.',
      successNotification: false,
      defaultEmpresa: {
        cnpj: "",
        razao_social: "",
        nome_fantasia: "",
        segmento: "Produto",
        inscricao_estadual: "",
        inscricao_municipal: "",
        telefone: "",
        cep: "",
        estado: "",
        bairro: "",
        logradouro: "",
        numero: "",
        complemento: ""
      }
    };
  },

  created() {
    this.getDataFromApi();
  },

  computed: {
    formTitle() {
      switch (this.mode) {
        case "ADD":
          return "Nova Empresa";
          break;
        case "EDIT":
          return "Editar Empresa";
        default:
          return "Empresa";
      }
    },
    pages() {
      if (
        this.pagination.rowsPerPage == null ||
        this.pagination.totalEmpresas == null
      )
        return 0;

      return Math.ceil(
        this.pagination.totalEmpresas / this.pagination.rowsPerPage
      );
    }
  },

  watch: {
    pagination: {
      handler() {
        this.getDataFromApi();
      },
      deep: true
    }
  },

  methods: {
    getEmpresas() {
      this.loading = true;
      return this.axios.get(this.endPoint).then(response => {
        this.loading = false;
        return response;
      });
    },

    getDataFromApi() {
      this.getEmpresas().then(response => {
        this.pagination.totalEmpresas = response.data.total;
        this.pagination.rowsPerPage = response.data.perPage;
        this.empresas = response.data.data;
      });
    },

    add() {
      this.dialog = true;
      this.mode = "ADD";
      this.editedEmpresa = Object.assign({}, this.defaultEmpresa);
    },

    editEmpresa(item) {
      this.editedEmpresa = item;
      this.editedIndex = this.empresas.indexOf(item);
      this.dialog = true;
      this.mode = "EDIT";
      this.disabled = ["disabled"];
    },

    viewEmpresa(item) {
      this.editedEmpresa = item;
      this.editedIndex = this.empresas.indexOf(item);
      this.dialog = true;
      this.mode = "VIEW";
    },

    deleteEmpresa(item) {
      this.axios.delete(this.endPoint + "/" + item.id, item);
      this.getDataFromApi();
    },

    cnpjChange(cnpj) {
      if (cnpj.length == 14) {
        this.axios("https://www.receitaws.com.br/v1/cnpj/" + cnpj).then(
          response => {
            if (response.data) {
              let { data } = response;
              Vue.set(this.editedEmpresa, "razao_social", data.nome);
              Vue.set(this.editedEmpresa, "nome_fantasia", data.fantasia);
              Vue.set(this.editedEmpresa, "cidade", data.municipio);
              Vue.set(this.editedEmpresa, "cep", data.cep);
              Vue.set(this.editedEmpresa, "estado", data.uf);
              Vue.set(this.editedEmpresa, "email", data.email);
              if (data.telefone) {
                let telefone = data.telefone.split("/")[0];
                Vue.set(this.editedEmpresa, "telefone", telefone);
              }
              Vue.set(this.editedEmpresa, "bairro", data.bairro);
              Vue.set(this.editedEmpresa, "logradouro", data.logradouro);
              Vue.set(this.editedEmpresa, "complemento", data.complemento);
              Vue.set(this.editedEmpresa, "numero", data.numero);
            }
          }
        );
      }
    },

    cepChange(cep) {
      if (cep.length == 8) {
        this.axios("https://viacep.com.br/ws/" + cep + "/json/").then(
          response => {
            if (response.data) {
              let { data } = response;
              Vue.set(this.editedEmpresa, "cidade", data.localidade);
              Vue.set(this.editedEmpresa, "estado", data.uf);
              Vue.set(this.editedEmpresa, "logradouro", data.logradouro);
              Vue.set(this.editedEmpresa, "bairro", data.bairro);
            }
          }
        );
      }
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedEmpresa = Object.assign({}, this.defaultEmpresa);
        this.editedIndex = -1;
        this.errors.clear();
        this.errorNotification   = false;
        this.successNotification = false;
      }, 300);
    },

    validaCnpj(cnpj) {
      cnpj = cnpj.replace(/[^\d]+/g, "");

      if (cnpj == "") return false;

      if (cnpj.length != 14) return false;

      // Valida DVs
      var tamanho = cnpj.length - 2;
      var numeros = cnpj.substring(0, tamanho);
      var digitos = cnpj.substring(tamanho);
      var soma = 0;
      var pos = tamanho - 7;
      for (var i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      var resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
      if (resultado != digitos.charAt(0)) return false;

      tamanho = tamanho + 1;
      numeros = cnpj.substring(0, tamanho);
      soma = 0;
      pos = tamanho - 7;
      for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
      if (resultado != digitos.charAt(1)) return false;

      return true;
    },

    save() {
      this.$validator.validateAll().then(valid => {
        if (valid) {
          if (this.validaCnpj(this.editedEmpresa.cnpj)) {
            if (this.mode == "ADD") {
              this.axios.post(this.endPoint, this.editedEmpresa).then(
                () => {
                  this.getDataFromApi();
                  this.errorNotification   = false;
                  this.successNotification = true;
                  setTimeout(() => {
                    this.dialog = false;
                    this.successNotification = false;
                  }, 1000);
                },
                error => {
                  this.errorNotification = true;
                  this.errorMessage = 'Ocorreram erros ao salvar os dados da empresa!';
                }
              );
            } else {
              this.axios
                .put(
                  this.endPoint + "/" + this.editedEmpresa.id,
                  this.editedEmpresa
                )
                .then(
                  () => {
                    this.getDataFromApi();
                    this.errorNotification   = false;
                    this.successNotification = true;
                    setTimeout(() => {
                      this.dialog = false;
                      this.successNotification = false;
                    }, 1000);
                  },
                  error => {
                    this.errorNotification = true;
                    this.errorMessage = 'Ocorreram erros ao salvar os dados da empresa!';
                  }
                );
            }
          }
          else {
            this.errorNotification = true;
            this.errorMessage = 'CNPJ inválido!';
          }
        }
      });
    },

    inscricaoRequired() {
      return true;
    }
  }
};
</script>