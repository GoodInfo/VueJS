<template> 
    <div class="alert_container">        
        <p  v-show="alert.display"
        class="alert alert-danger" 
        id="error_message" 
        transition="fade" >{{ alert.message }}</p>
    </div>
    <table class="table table-striped">
        <tr>
            <th>Categoría</th>
            <th>Nota</th>
            <th width="50px">&nbsp;</th>
        </tr>
        <tr 
            v-for="note in notes" 
            is="note-row" 
            :note.sync="note" 
            :categories="categories"
            @update-note="updateNote"
            @delete-note="deleteNote">
        </tr>
        <tr>
            <td><select-category :categories="categories" :id.sync="new_note.category_id"></select-category></td>
            <td>
                <input type="text" v-model="new_note.note" class="form-control">
                <ul v-if="errors.length" class="text-danger">  
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </td>
            <td>
                <a href="#" @click="createNote">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </a>
            </td>
        </tr>
    </table>
</template>
<script>

var Vue = require('vue');
Vue.use(require('vue-resource'));
var resource;
var utils = require('./../utils');
export default{
    
    data: function() {
        return {  
            new_note: {
            note: '',
            category_id: ''
            },
            notes: [],
            errors: [],
            alert: {
            message: '',
            display: false
            },
            categories: [
            {
            id: 1,
            name: 'Laravel'
            },
            {
            id: 2,
            name: 'Vue.js'
            },
            {
            id: 3,
            name: 'Publicidad'
            }
            ]
        }
    },
    ready: function () {
        resource = this.$resource('/vuej/ApiLaravel/public/api/v1/notes{/id}');

        resource.get().then(function (response) {
            this.notes = response.data;
        });

        Vue.http.interceptors.push(function (request, next) {
            next(function (response) {
                if (response.ok) {
                    return response;
                }

                this.alert.message = response.data.message;
                this.alert.display = true;

                setTimeout(function () {
                    vm.alert.display = false;
                }, 4000);

                return response;
            });
        });
    },
    methods: {
        createNote: function () {
            this.errors = [];

            resource.save({}, this.new_note).then(function (response) {
                this.notes.push(response.data.note);
            }, function (response) {
                this.errors = response.data.errors;
            });

            this.new_note = {note: '', category_id: ''};
        },
        deleteNote: function (note) {
            resource.delete({id: note.id}).then(function (response) {
                this.notes.$remove(note);
            });
        },
        updateNote: function (component) {
            resource.update({id: component.note.id}, component.draft).then(function (response) {

                utils.assign(component.note, response.data.note);

                component.editing = false;
            }, function (response) {
                component.errors = response.data.errors;
            });
        }
    },
    filters: {
    }
}
</script>