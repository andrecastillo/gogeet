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
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(principle, index) in principles" @click="seeDetails(index)">
                <td>{{ principle.name }}</td>
                <td>
                    <button @click="deletePrinciple(index)" class="btn btn-danger btn-xs" style="padding:8px"><span
                        class="glyphicon glyphicon-trash"></span></button>
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
            }
        },

        created() {
            this.$root.$on('updateName', this.updateName);
        },

        mounted () {
            this.readPrinciples()
        },

        methods: {

            readPrinciples () {
                axios.get('/principle')
                    .then(response => {
                        this.principles = response.data.principles
                    })
            },

            createPrinciple () {
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

            deletePrinciple (index) {
                let conf = confirm('Do you ready want to delete this principle?')
                if (conf === true) {
                    axios.delete('/principle/' + this.principles[index].id)
                        .then(response => {
                            this.principles.splice(index, 1)
                        })
                        .catch(error => {
                        })
                }
            },

            seeDetails (index) {
                this.$root.$emit('loadDetails', {
                    id: this.principles[index].id,
                    index: index
                });
            },

            updateName(data) {
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
