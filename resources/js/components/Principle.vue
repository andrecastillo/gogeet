Principle.vue:
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading ml-0 mt-2 mr-0 mb-2">
                        <button @click="_initAddPrinciple()" class="btn btn-success " style="padding:5px">
                            Add New Principle
                        </button>
                    </div>

                    <div class="panel-body">
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
                                        <button @click="_initUpdatePrinciple(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
                                        <button @click="deletePrinciple(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

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
                            <input type="text" name="name" id="name" placeholder="Principle Name" class="form-control" v-model="principle.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Principle Description" v-model="principle.description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createPrinciple" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_principle_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Principle</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" placeholder="Principle Name" class="form-control"
                                   v-model="update_principle.name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Principle Description" v-model="update_principle.description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updatePrinciple" class="btn btn-primary">Submit</button>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

    </div>
</template>

<script>

    export default {
        name: 'Principle',

        data(){
            return {
                principle: {
                    name: '',
                    description: '',
                },
                errors: [],
                principles: [],
                update_principle: {}
            }
        },

        mounted()
        {
            this.readPrinciples();
        },

        methods: {

            readPrinciples()
            {
                axios.get('/principle')
                    .then(response => {
                        this.principles = response.data.principles;
                    });
            },

            createPrinciple()
            {
                axios.post('/principle', {
                    name: this.principle.name,
                    description: this.principle.description,
                })
                .then(response => {
                    this._reset();
                    this.principles.push(response.data.principles);
                    $("#add_principle_model").modal("hide");
                })
                .catch(error => {
                    this.errors = [];
                    if (error.response.data.errors && error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if (error.response.data.errors && error.response.data.errors.description)
                    {
                        this.errors.push(error.response.data.errors.description[0]);
                    }
                });
            },

            updatePrinciple()
            {
                axios.patch('/principle/' + this.update_principle.id, {
                    name: this.update_principle.name,
                    description: this.update_principle.description,
                })
                    .then(response => {
                        $("#update_principle_model").modal("hide");
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }
                        if (error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0]);
                        }
                    });
            },

            deletePrinciple(index)
            {
                let conf = confirm("Do you ready want to delete this principle?");
                if (conf === true) {
                    axios.delete('/principle/' + this.principles[index].id)
                        .then(response => {
                            this.principles.splice(index, 1);
                        })
                        .catch(error => {
                        });
                }
            },

            seeDetails(index)
            {
                this.$root.$emit('loadDetails', this.principles[index].id);
            },

            _initAddPrinciple()
            {
                this.errors = [];
                $("#add_principle_model").modal("show");
            },

            _initUpdatePrinciple(index)
            {
                this.errors = [];
                $("#update_principle_model").modal("show");
                this.update_principle = this.principles[index];
            },

            _reset()
            {
                this.principle.name = '';
                this.principle.description = '';
            },

            _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
                return date === null ? '' : moment(date).format(format);
            },

        }
    }

</script>
