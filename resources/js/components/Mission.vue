Mission.vue:
<template>
    <div>

        <div class="ml-0 mt-2 mr-0 mb-2">
            <button @click="_initAddMission()" class="btn btn-success " style="padding:5px">
                Add New Mission
            </button>
        </div>

        <table class="table table-responsive-lg table-sm table-hover" v-if="missions.length > 0">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Due Date</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(mission, index) in missions" @click="seeDetails(index)" v-bind:class="{'table-active':(index == active)}">
                <td>{{ mission.name }}</td>
                <td>{{ _formatDateTime(mission.due_date, 'MM/DD/YYYY') }}</td>
                <td class="text-center">
                    <i class="fa fa-trash fa-1 cursor-pointer" aria-hidden="true" @click.stop="deleteMission(index)"></i>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- add modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="add_mission_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Mission</h4>
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Mission Name" class="form-control"
                                   v-model="new_mission.name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Mission Description" v-model="new_mission.description"></textarea>
                        </div>

                        <div class="form-group force-inline">
                            <label for="due_date">Due Date:</label>
                            <datepicker name="due_date" id="due_date" placeholder="Due Date" v-model="new_mission.due_date"
                                        :format="date_format" :clear-button="clear_button"></datepicker>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createMission" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /add modal -->

    </div>
</template>

<script>

    import Datepicker from 'vuejs-datepicker';

    export default {
        name: 'Mission',
        components: {
            Datepicker,
        },
        data () {
            return {
                missions: [],
                new_mission: {
                    name: '',
                    description: '',
                    due_date: '',
                },
                errors: [],
                date_format: 'MM/dd/yyyy',
                clear_button: true,
                active: null,
            }
        },

        created () {
            this.$root.$on('updateRow', this.updateRow);
        },

        mounted () {
            this.readMissions()
        },

        methods: {

            readMissions () {
                axios.get('/mission')
                    .then(response => {
                        this.missions = response.data.missions
                    })
            },

            createMission () {
                axios.post('/mission', {
                    name: this.new_mission.name,
                    description: this.new_mission.description,
                    due_date: this.new_mission.due_date === null || this.new_mission.due_date === '' ? null : moment(this.new_mission.due_date).format('YYYY-MM-DD')
                })
                    .then(response => {
                        this._reset()
                        this.missions.push(response.data.missions)
                        $('#add_mission_model').modal('hide')
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

            deleteMission (index) {
                let conf = confirm('Do you really want to delete this mission?')
                if (conf === true) {
                    axios.delete('/mission/' + this.missions[index].id)
                        .then(response => {
                            this.missions.splice(index, 1)
                            this.nextOrPrevMission(index);
                        })
                        .catch(error => {
                        })
                }
            },

            nextOrPrevMission: function(index) {
                if (this.active !== null) {
                    // the one that was deleted was not the last one but the last one was selected
                    if(index !== this.active && this.active === this.missions.length) {
                        this.active = this.missions.length - 1;
                    }
                    // the one that was deleted was the one selected but it was not the last one
                    if (index === this.active && this.active !== this.missions.length) {
                        this.$root.$emit('missionDeleted')
                        this.seeDetails(index);
                    }
                    // the one that was deleted was the one that was selected and it was the last one
                    if(index === this.active && this.active === this.missions.length) {
                        this.$root.$emit('missionDeleted')
                        this.seeDetails(index - 1);
                    }
                }
            },

            seeDetails (index) {
                this.active = index;
                this.$root.$emit('loadDetails', {
                    id: this.missions[index].id,
                    index: index,
                });
            },

            updateRow: function(data) {
                this.missions[data.index].name = data.new_name;
                this.missions[data.index].due_date = data.new_due_date;
            },

            _initAddMission () {
                this.errors = []
                $('#add_mission_model').modal('show')
            },

            _reset () {
                this.mission.name = ''
                this.mission.description = ''
                this.mission.due_date = ''
            },

            _formatDateTime (date, format = 'MM/DD/YYYY') {
                return date === null ? '' : moment(date).format(format)
            },

        }
    }
</script>
