Principle.vue:
<template>
    <div>

        <div class="ml-0 mt-2 mr-0 mb-2">
            <button @click="_initAddPrinciple()" class="btn btn-success " style="padding:5px">
                Add New Principle
            </button>
        </div>

        <table class="table table-responsive-lg table-sm table-hover" v-if="principles.length > 0">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(principle, index) in principles" @click="seeDetails(index)" v-bind:class="{'table-active':(index == active)}">
                <td>{{ principle.name }}</td>
                <td class="text-center">
                    <i class="fa fa-trash fa-1 cursor-pointer" aria-hidden="true" @click.stop="deletePrinciple(index)"></i>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- add modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="add_principle_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Principle</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="names">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Principle Name" class="form-control"
                                   v-model="new_principle.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Principle Description" v-model="new_principle.description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createPrinciple" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /add modal -->

    </div>
</template>

<script>

    export default {
        name: 'Principle',

        data () {
            return {
                new_principle: {
                    name: '',
                    description: '',
                },
                errors: [],
                principles: [],
                active: null,
            }
        },

        created () {
            this.$root.$on('updateName', this.updateName);
        },

        mounted () {
            this.readPrinciples()
        },

        methods: {

            readPrinciples: function() {
                axios.get('/principle')
                    .then(response => {
                        this.principles = response.data.principles
                    })
            },

            createPrinciple: function() {
                axios.post('/principle', {
                    name: this.new_principle.name,
                    description: this.new_principle.description,
                })
                    .then(response => {
                        this._reset()
                        this.principles.push(response.data.principles)
                        $('#add_principle_model').modal('hide')
                    })
                    .catch(error => {
                        this.errors = []
                        if (error.response.data.errors && error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0])
                        }
                        if (error.response.data.errors && error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0])
                        }
                    })
            },

            deletePrinciple: function(index) {
                let conf = confirm('Do you really want to delete this principle?')
                if (conf === true) {
                    axios.delete('/principle/' + this.principles[index].id)
                        .then(response => {
                            this.principles.splice(index, 1)
                            this.nextOrPrevPrinciple(index);
                        })
                        .catch(error => {
                        })
                }
            },

            nextOrPrevPrinciple: function(index) {
                if (this.active !== null) {
                    // the one that was deleted was not the last one but the last one was selected
                    if(index !== this.active && this.active === this.principles.length) {
                        this.active = this.principles.length - 1;
                    }
                    // the one that was deleted was the one selected but it was not the last one
                    if (index === this.active && this.active !== this.principles.length) {
                        this.$root.$emit('principleDeleted')
                        this.seeDetails(index);
                    }
                    // the one that was deleted was the one that was selected and it was the last one
                    if(index === this.active && this.active === this.principles.length) {
                        this.$root.$emit('principleDeleted')
                        this.seeDetails(index - 1);
                    }
                }
            },

            seeDetails: function(index) {
                this.active = index;
                this.$root.$emit('loadDetails', {
                    id: this.principles[index].id,
                    index: index,
                });
            },

            updateName: function(data) {
                this.principles[data.index].name = data.new_name;
            },

            _initAddPrinciple () {
                this.errors = []
                $('#add_principle_model').modal('show')
            },

            _reset () {
                this.new_principle.name = ''
                this.new_principle.description = ''
            },

            _formatDateTime (date, format = 'MM/DD/YYYY hh:mm A') {
                return date === null ? '' : moment(date).format(format)
            },

        }
    }

</script>

