@if(Request::is('principles'))
    <principle-details></principle-details>
@endif
@if(Request::is('missions'))
    <mission-details></mission-details>
@endif
@if(Request::is('goals'))
    <goal-details></goal-details>
@endif
@if(Request::is('tasks'))
    <task-details></task-details>
@endif
