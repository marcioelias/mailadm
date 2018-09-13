<template>
    <div v-bind:class="{ 'col': true, 'has-error': this.hasErrors }">
        <div v-bind:class="{ 'panel': true, 'panel-default': true, 'panel-danger': this.hasErrors}">
            <div class="panel-heading">
                <span style="color: white" v-if="this.hasErrors">
                    <strong>Membros</strong>
                </span>
                <span v-if="!this.hasErrors">
                    <strong>Membros</strong>
                </span>
            </div>
            <div class="panel-body" style="padding: 0 !important;">
                <table class="table table-condensed table-striped table-bordered table-hover" style="margin-bottom:0 !important;">
                    <thead>
                        <tr class="primary">
                            <th class="col-md-9">E-mail</th>
                            <th class="col-md-2">Ativo</th>
                            <th class="col-md-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody name="fade" is="transition-group">
                        <tr v-for="(item, index) in items" :key="index">
                            <td class="col-md-9">
                                {{ item.forwarding }}
                                <input type="hidden" :name="'membros['+index+'][forwarding]'" :value="item.forwarding">
                            </td>
                            <td class="col-md-2">
                                {{ (item.itemActive == '1') ? 'Sim' : 'Não' }}
                                <input type="hidden" :name="'membros['+index+'][itemActive]'" :value="item.itemActive">
                            </td>
                            <td class="col-md-1">
                                <button type="button" class="btn-xs btn-warning" @click="editItem(index)" v-show="!editing">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                                <button type="button" class="btn-xs btn-danger" @click="confirmDelete(index)" data-toggle="modal" data-target="#confirmDelete" v-show="!editing">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div v-bind:class="{'col-md-9': true, ' has-error': this.errors.inputEmail}" style="padding-right: 0 !important; padding-left: 0 !important;">
                        <select ref="inputEmails" v-model="forwarding" data-live-search="true" class="form-control selectpicker" name="inputEmail" id="inputEmail" :disabled="editing" >
                            <option selected value="false"> Nada Selecionado </option>
                            <option v-for="(email, index) in emailsDisponiveisOrdenados" :value="email.username" :key="index">{{ email.username }}</option>
                        </select>
                        <span class="help-block" :v-if="this.errors.inputEmail">
                            <strong>{{ this.errors.inputEmailMsg }}</strong>
                        </span>
                    </div>
                    <div v-bind:class="{'col-md-2': true}" style="padding-right: 0 !important; padding-left: 0 !important;" align="center">
                        <bootstrap-toggle 
                            v-model="itemActive" 
                            :options="{ 
                                on: 'Sim', 
                                off: 'Não',
                            }" 
                            :disabled="false" />                
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" @click="addEmail" v-show="!editing">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        <button type="button" class="btn btn-success" @click="updateEmail" v-show="editing">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                    </div>
                </div>
            </div>
            <modal @cancel="cancelDelete" @confirm="deleteItem" :modal-title="'Corfirmação'" :modal-text="'Confirma a remoção deste Item?'" />
            <span class="help-block" style="margin: 5px;" v-if="this.hasErrors">
                <strong>{{ this.errorMsg }}</strong>
            </span>
        </div>
    </div>
</template>

<script>
    import BootstrapToggle from 'vue-bootstrap-toggle'
    import modal from './modal.vue';

    export default {
        name: 'alias_members',
        components: {
            modal,
            BootstrapToggle
        },
        data() {
            return {
                editing: false,
                editingIndex: false,
                items: [],
                forwarding: false,
                itemActive: true,
                isModalVisible: false,
                deleteIndex: false,
                emailsDisponiveis: [],
                emailsSelecionados: [],
                errors: {
                    inputEmail: false,
                    inputEmailMsg: '',
                }
            }
        },
        props: [
            'aliasMembersData',
            'oldData',
            'hasErrors',
            'errorMsg'
        ],
        watch: {
            oldData: function() {
               this.$refs.confirmDelete
            },
        },
        computed: {
            emailsDisponiveisOrdenados: function() {
                function compare(a, b) {
                    if (a.username < b.username)
                        return -1;
                    if (a.username > b.username)
                        return 1;
                    return 0;
                }

                return this.emailsDisponiveis.sort(compare);
            },
        },
        updated() {
            $(this.$refs.inputEmails).selectpicker('refresh');
        },
        mounted() {
            this.emailsDisponiveis = this.aliasMembersData;
            if (this.oldData !== null) {
                for (var i=0; i<this.oldData.length; i++) {  
                    var act = false;
                    if (this.oldData[i].hasOwnProperty('itemActive')) {
                        act = this.oldData[i].itemActive;
                    }
                    if (this.oldData[i].hasOwnProperty('active')) {
                        act = this.oldData[i].active;
                    }
                    this.items.push({
                        'forwarding': this.oldData[i].forwarding,
                        'itemActive': act,
                    });
                    this.incluirEmail(this.oldData[i].forwarding);
                }
            }
        },
        updated() {
            $(this.$refs.inputEmails).selectpicker('refresh');
        },
        methods: {
            validarItem() {
                if ((this.forwarding == '') || (this.forwarding <= 0)) {
                    this.errors.inputEmail = true;
                    this.errors.inputEmailMsg = 'Nenhum E-mail selecionado.';
                    return false;
                } else {
                    this.errors.inputEmail = false;
                    this.errors.inputEmailMsg = '';
                }
                
                return true;
            },
            confirmDelete(index) {
                this.deleteIndex = index;
            },
            cancelDelete(index) {
                this.deleteIndex = false;
            },
            addEmail() {
                if (this.validarItem()) {
                    this.items.push({
                        'forwarding': this.forwarding,
                        'itemActive': this.itemActive,
                    });
                    this.incluirEmail(this.forwarding);
                    this.limparFormulario();
                }
            },
            editItem(index) {
                let item = this.items[index];
                //$(this.$refs.inputEmails).prop('checked', item.itemActive);
                this.forwarding = item.forwarding;
                this.itemActive = item.itemActive;
                this.editing = true;
                this.editingIndex = index;
            },
            updateEmail() {
                if (this.validarItem()) {
                    this.items[this.editingIndex] = {
                        'forwarding': this.forwarding,
                        'itemActive': this.itemActive
                    };
            
                    this.editing = false;
                    this.editingIndex = false;
                    this.limparFormulario();
                }
            },
            deleteItem() {
                this.removerEmail(this.items[this.deleteIndex].forwarding);
                this.$delete(this.items, this.deleteIndex);
            },
            limparFormulario() {
                this.forwarding = false;
                this.itemActive = true;
                this.$refs.inputEmails.focus(); 
            },
            getEmailById(id) {
                let result = 0;
                for (var i=0; i<this.aliasMembersData.length; i++) {  
                    if (this.aliasMembersData[i].id == id) {
                        result = this.aliasMembersData[i];
                        break;
                    } 
                }
                return result;
            },
            getEmailIndexById(id) {
                let result = 0;
                for (var i=0; i<this.aliasMembersData.length; i++) {  
                    if (this.aliasMembersData[i].id == id) {
                        result = i;
                        break;
                    } 
                }
                return result;
            },
            getEmailSelecionadoById(id) {
                let result = 0;
                for (var i=0; i<this.emailsSelecionados.length; i++) {  
                    if (this.emailsSelecionados[i].id == id) {
                        result = this.emailsSelecionados[i];
                        break;
                    } 
                }
                return result;
            },
            getEmailSelecionadoIndexById(id) {
                let result = 0;
                for (var i=0; i<this.emailsSelecionados.length; i++) {  
                    if (this.emailsSelecionados[i].id == id) {
                        result = i;
                        break;
                    } 
                }
                return result;
            },
            incluirEmail(id) {
                this.emailsSelecionados.push(this.getEmailById(id));
                this.$delete(this.emailsDisponiveis, this.getEmailIndexById(id));
            },
            removerEmail(id) {
                this.emailsDisponiveis.push(this.getEmailSelecionadoById(id));
                this.$delete(this.emailsSelecionados, this.getEmailSelecionadoIndexById(id));
            } 
        }
    }
</script>