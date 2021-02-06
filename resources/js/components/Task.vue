Task.vue:
<template>
    <div>

        <div class="ml-0 mt-2 mr-0 mb-2">
            <button @click="_initAddTask()" class="btn btn-success " style="padding:5px">
                Add New Task
            </button>
        </div>

        <table class="table table-responsive-lg table-sm table-hover" v-if="tasks.length > 0">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Due Date</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks" @click="seeDetails(index)">
                <td>{{ task.name }}</td>
                <td>{{ _formatDateTime(task.due_date, 'MM/DD/YYYY') }}</td>
                <td class="text-center">
                    <i class="fa fa-trash fa-1 cursor-pointer" aria-hidden="true" @click.stop="deleteTask(index)"></i>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- add modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="add_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Task</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Task Name" class="form-control"
                                   v-model="new_task.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="new_task.description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date:</label>
                            <datepicker name="due_date" id="due_date" placeholder="Due Date" v-model="new_task.due_date"
                                        :format="date_format" :clear-button="clear_button"></datepicker>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createTask" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /add modal -->

    </div>
</template>

<script>

    import Datepicker from 'vuejs-datepicker'

    export default {
        name: 'Task',
        components: {
            Datepicker
        },
        data () {
            return {
                tasks: [],
                new_task: {
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
            this.readTasks()
        },

        methods: {

            readTasks () {
                axios.get('/task')
                    .then(response => {
                        this.tasks = response.data.tasks
                    })
            },

            createTask () {
                axios.post('/task', {
                    name: this.task.name,
                    description: this.task.description,
                    due_date: this.task.due_date === null || this.task.due_date === '' ? null : moment(this.task.due_date).format('YYYY-MM-DD')
                })
                    .then(response => {
                        this._reset()
                        this.tasks.push(response.data.tasks)
                        $('#add_task_model').modal('hide')
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

            deleteTask (index) {
                let conf = confirm('Do you ready want to delete this task?')
                if (conf === true) {
                    axios.delete('/task/' + this.tasks[index].id)
                        .then(response => {
                            this.tasks.splice(index, 1)
                            this.nextOrPrevTask(index);
                        })
                        .catch(error => {
                        })
                }
            },

            nextOrPrevTask: function(index) {
                if (this.active !== null) {
                    // the one that was deleted was not the last one but the last one was selected
                    if(index !== this.active && this.active === this.tasks.length) {
                        this.active = this.tasks.length - 1;
                    }
                    // the one that was deleted was the one selected but it was not the last one
                    if (index === this.active && this.active !== this.tasks.length) {
                        this.$root.$emit('taskDeleted')
                        this.seeDetails(index);
                    }
                    // the one that was deleted was the one that was selected and it was the last one
                    if(index === this.active && this.active === this.tasks.length) {
                        this.$root.$emit('taskDeleted')
                        this.seeDetails(index - 1);
                    }
                }
            },

            seeDetails (index) {
                this.active = index;
                this.$root.$emit('loadDetails', {
                    id: this.tasks[index].id,
                    index: index,
                });
            },

            updateRow: function(data) {
                this.tasks[data.index].name = data.new_name;
                this.tasks[data.index].due_date = data.new_due_date;
            },

            _initAddTask () {
                this.errors = []
                $('#add_task_model').modal('show')
            },

            _reset () {
                this.task.name = ''
                this.task.description = ''
                this.task.due_date = ''
            },

            _formatDateTime (date, format = 'MM/DD/YYYY') {
                return date === null ? '' : moment(date).format(format)
            },
        }
    }

</script>
