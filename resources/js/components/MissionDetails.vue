<template>
<div class="container">

    <input
        class="form-control form-control-lg mt-2 w-100"
        @focusout="editMission"
        v-model="mission.name"
    >

    <div class="ml-3">
        <div class="row mt-2">
            <div class="col-2">
                <label class="col-2">Description: </label>
            </div>
            <div class="col">
                <textarea
                    id="description"
                    class="form-control"
                    rows="5"
                    style="resize: none;"
                    @focusout="editMission"
                    placeholder="-----"
                    v-model="mission.description">
                </textarea>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-2">
                <label class="col-2">Accomplish: </label>
            </div>
            <div class="col">
                <datepicker
                    name="due_date"
                    id="modify_due_date"
                    placeholder="Due Date"
                    @closed="editMission"
                    @cleared="editMission"
                    v-model="mission.due_date"
                    :format="date_format"
                    :clear-button="clear_button">
                </datepicker>
            </div>
        </div>

        <div class="blockquote-footer">
            <p>Last Update: {{ _formatDateTime(mission.updated_at) }}</p>
        </div>
    </div>

    <!-- possibly where i put errors
    <div class="alert alert-danger" v-if="errors.length > 0">
        <ul class="m-0">
            <li v-for="error in errors">{{ error }}</li>
        </ul>
    </div>
    -->

</div>

</template>

<script>

import Datepicker from 'vuejs-datepicker';


export default {
    name: 'MissionDetails',
    components: {
        Datepicker,
    },
    data(){
        return {
            mission: {
                created_at: '',
                deleted_at: '',
                description: '',
                id: 0,
                name: '',
                updated_at: '',
                user_id: '',
                due_date: '',
            },
            index: null,
            original_name: null,
            original_description: null,
            original_due_date: null,
            date_format: 'MM/dd/yyyy',
            clear_button: true,
        }
    },
    created() {
        this.$root.$on('loadDetails', this.getDetails);
        this.$root.$on('missionDeleted', this.missionDeleted);
    },
    methods: {

        getDetails: function (data)
        {
            this.index = data.index
            axios.get('/mission/'+data.id)
            .then(response => {
                this.mission = response.data.mission;
                this.mission.due_date = response.data.mission.due_date === null ? null : moment(response.data.mission.due_date).format('MM/DD/YYYY');
                this.original_name = this.mission.name;
                this.original_description = this.mission.description;
                this.original_due_date = this.mission.due_date;
            });
        },

        editMission: function()
        {
            if (this.original_name !== this.mission.name || this.original_description !== this.mission.description || this.original_due_date !== moment(this.mission.due_date).format('MM/DD/YYYY')) {
                axios.patch('/mission/' + this.mission.id, {
                    name: this.mission.name,
                    description: this.mission.description,
                    due_date: this.mission.due_date === null || this.mission.due_date ===  '' ? null : moment(this.mission.due_date).format("YYYY-MM-DD")
                })
                .then(response => {
                    // update last updated text
                    this.mission.updated_at = response.data.updated_at;

                    // now update the value in the sibling component if it was name or due date not description
                    this.$root.$emit('updateRow', {
                        new_name: this.mission.name,
                        new_due_date: this.mission.due_date,
                        index: this.index
                    });
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
            }
        },

        missionDeleted: function() {
            this._reset();
        },

        _reset() {
            this.mission.name = ''
            this.mission.description = ''
            this.mission.due_date = ''
        },

        _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
            return date === null ? '' : moment(date).format(format);
        },

    }
}

</script>
