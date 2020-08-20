GoalRow.vue:
<template>
    <tr>
        <td>{{ goal.name }}</td>
        <td>{{ goal.description }}</td>
        <td>{{ goal.created_at }}</td>
        <td>{{ goal.updated_at }}</td>
        <td><button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
            <button @click="deleteGoal(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
        </td>
    </tr>
</template>


<script>

export default {
    name: 'goal-row',
    props: {
        goal: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        }
    }
}

</script>
