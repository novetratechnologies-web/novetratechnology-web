<template>
    <div class="cd-vue-list">
        <div v-for="user in users" :key="user.id" class="cd-vue-person">
            <img :src="user.avatar || url+'/uploads/user/avatar/default-user.jpg'" alt="" @error="$event.target.src=url+'/uploads/user/avatar/default-user.jpg'">
            <a :href="url+'/admin/member/show/'+user.name"><strong>{{ user.fullname || 'Member' }}</strong><small>Birthday today <span v-if="user.age">· {{ user.age }} years</span></small></a>
            <i class="fas fa-cake-candles"></i>
        </div>
        <div v-if="users.length === 0" class="cd-component-empty"><span><i class="fas fa-cake-candles"></i></span><strong>No birthdays today</strong><small>Check back tomorrow for celebrations.</small></div>
        <a v-if="users.length" :href="url+'/admin/dashboard/birthday'" class="cd-vue-action"><i class="fas fa-paper-plane"></i> Send birthday wishes</a>
    </div>
</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                users: [],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getMember()
            {
                axios.get('/admin/dashboard/showBirthday').then(response => {
                    this.users = response.data.data;
                });
            },
        },

        created()
        {
            this.getMember();
        }
    }
</script>
