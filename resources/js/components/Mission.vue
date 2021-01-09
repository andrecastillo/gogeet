Mission.vue:
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3><span class="glyphicon glyphicon-dashboard"></span> Missions </h3> <br>
                        <button @click="initAddMission()" class="btn btn-success " style="padding:5px">
                            Add New Mission
                        </button>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="missions.length > 0">
                            <tbody>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(mission, index) in missions">
                                <td>{{ index + 1 }}</td>
                                <td>{{ mission.name }}</td>
                                <td>{{ mission.description }}</td>
                                <td>{{ _formatDateTime(mission.created_at) }}</td>
                                <td>{{ _formatDateTime(mission.updated_at) }}</td>
                                <td><button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
                                    <button @click="deleteMission(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_mission_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Mission</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="names">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Mission Name" class="form-control" v-model="mission.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Mission Description" v-model="mission.description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createMission" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_mission_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Mission</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" placeholder="Mission Name" class="form-control"
                                   v-model="update_mission.name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Mission Description" v-model="update_mission.description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateMission" class="btn btn-primary">Submit</button>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

    </div>
</template>

<script>

    export default {
        name: 'mission',
        data(){
            return {
                mission: {
                    name: '',
                    description: '',
                    created_at: '',
                    updated_at: ''
                },
                errors: [],
                missions: [],
                update_mission: {}
            }
        },

        mounted()
        {
            this.readMissions();
        },
        methods: {
            deleteMission(index)
            {
                let conf = confirm("Do you ready want to delete this mission?");
                if (conf === true) {
                    axios.delete('/mission/' + this.missions[index].id)
                        .then(response => {
                            this.missions.splice(index, 1);
                        })
                        .catch(error => {
                        });
                }
            },
            initAddMission()
            {
                $("#add_mission_model").modal("show");
            },
            createMission()
            {
                axios.post('/mission', {
                    name: this.mission.name,
                    description: this.mission.description,
                })
                    .then(response => {
                        this.reset();
                        this.missions.push(response.data.missions);
                        $("#add_mission_model").modal("hide");
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
            reset()
            {
                this.mission.name = '';
                this.mission.description = '';
            },
            readMissions()
            {
                axios.get('/mission')
                    .then(response => {
                        this.missions = response.data.missions;
                    });
            },
            initUpdate(index)
            {
                this.errors = [];
                $("#update_mission_model").modal("show");
                this.update_mission = this.missions[index];
            },
            updateMission()
            {
                axios.patch('/mission/' + this.update_mission.id, {
                    name: this.update_mission.name,
                    description: this.update_mission.description,
                })
                .then(response => {
                    $("#update_mission_model").modal("hide");
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
            _formatDateTime(date, format = "MM/DD/YYYY hh:mm A") {
                return date === null ? '' : moment(date).format(format);
            },
        }
    }

</script>
