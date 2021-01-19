Task.vue:
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading ml-0 mt-2 mr-0 mb-2">
                        <button @click="_initAddTask()" class="btn btn-success " style="padding:5px">
                            Add New Task
                        </button>
                    </div>

                    <div class="panel-body">
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
                                    <td>
                                        <button @click="_initUpdateTask(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
                                        <button @click="deleteTask(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- add task modal -->
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
                            <input type="text" name="name" id="name" placeholder="Task Name" class="form-control" v-model="task.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Task Description" v-model="task.description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date:</label>
                        <datepicker name="due_date" id="due_date" placeholder="Due Date" v-model="task.due_date" :format="date_format" :clear-button="clear_button"></datepicker>

                    </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createTask" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Task</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" placeholder="Task Name" class="form-control"
                                   v-model="update_task.name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="update_task.description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="update_due_date">Due Date:</label>
                            <datepicker name="update_due_date" id="update_due_date" placeholder="Due Date" v-model="update_task.due_date" :format="date_format" :clear-button="clear_button"></datepicker>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateTask" class="btn btn-primary">Submit</button>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    name: 'Task',
    components: {
      Datepicker
    },
    data(){
        return {
            date_format: "MM/dd/yyyy",
            clear_button: true,
            task: {
                name: '',
                description: '',
            },
            errors: [],
            tasks: [],
            update_task: {}
        }
    },

    mounted()
    {
        this.readTasks();
    },

    methods: {

        readTasks()
        {
            axios.get('/task')
                .then(response => {
                    this.tasks = response.data.tasks;
                });
        },

        createTask()
        {
            axios.post('/task', {
                name: this.task.name,
                description: this.task.description,
                due_date: this.task.due_date === null || this.task.due_date === '' ? null : moment(this.task.due_date).format('YYYY-MM-DD')
            })
            .then(response => {
                this._reset();
                this.tasks.push(response.data.tasks);
                $("#add_task_model").modal("hide");
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

        updateTask()
        {
            axios.patch('/task/' + this.update_task.id, {
                name: this.update_task.name,
                description: this.update_task.description,
                due_date: this.update_task.due_date === null || '' ? null : moment(this.update_task.due_date).format("YYYY-MM-DD")
            })
                .then(response => {
                    $("#update_task_model").modal("hide");
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

        deleteTask(index)
        {
            let conf = confirm("Do you ready want to delete this task?");
            if (conf === true) {
                axios.delete('/task/' + this.tasks[index].id)
                    .then(response => {
                        this.tasks.splice(index, 1);
                    })
                    .catch(error => {
                    });
            }
        },

        seeDetails(index)
        {
            this.$root.$emit('loadDetails', this.tasks[index].id);
        },

        _initAddTask()
        {
            this.errors = [];
            $("#add_task_model").modal("show");
        },

        _initUpdateTask(index)
        {
            this.errors = [];
            $("#update_task_model").modal("show");
            this.update_task = this.tasks[index];
            this.update_task.due_date = this.update_task.due_date === null ? null : moment(this.update_task.due_date).format('MM/DD/YYYY');
        },

        _reset()
        {
            this.task.name = '';
            this.task.description = '';
            this.task.due_date = '';
        },

        _formatDateTime(date, format = "MM/DD/YYYY") {
            return date === null ? '' : moment(date).format(format);
        },
    }
}

</script>
